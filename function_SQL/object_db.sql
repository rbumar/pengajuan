CREATE TABLE public."role" (
	id_role serial4 NOT NULL,
	nama_role varchar NULL,
	role_level int4 NULL,
	deskripsi varchar NULL,
	CONSTRAINT role_pkey PRIMARY KEY (id_role)
);

CREATE TABLE public.users (
	id serial4 NOT NULL,
	email varchar NULL,
	"password" varchar NULL,
	id_role varchar NULL,
	CONSTRAINT users_pkey PRIMARY KEY (id)
);

CREATE TABLE public.global_param (
	id int4 NULL,
	"name" text NULL,
	data_type text NULL,
	value text NULL
);

CREATE TABLE public.barang (
	id_barang varchar NOT NULL,
	nama_barang varchar NULL,
	deskripsi varchar NULL,
	CONSTRAINT barang_pkey PRIMARY KEY (id_barang)
);

create sequence barang_seq start 1;

CREATE TABLE public.stok (
	id_stok serial4 NOT NULL,
	id_barang varchar NULL,
	stok int4 NULL,
	CONSTRAINT stok_pkey PRIMARY KEY (id_stok)
);


-- public.v_karyawan source

CREATE OR REPLACE VIEW public.v_karyawan
AS SELECT k.id_karyawan,
    k.nama_karyawan,
    k.alamat,
    k.email,
    k.id_role,
    r.nama_role,
    r.role_level,
    r.deskripsi
   FROM karyawan k
     JOIN role r ON k.id_role::text = r.id_role::character varying::text;


-- public.v_users source

CREATE OR REPLACE VIEW public.v_users
AS SELECT u.id,
    u.email,
    u.password,
    k.id_role,
    k.id_karyawan,
    k.nama_karyawan,
    k.alamat,
    r.nama_role,
    r.role_level,
    r.deskripsi
   FROM users u
     JOIN karyawan k ON u.email::text = k.email
     JOIN role r ON r.id_role::character varying::text = k.id_role::text;


-- public.v_stock source

CREATE OR REPLACE VIEW public.v_stock
AS SELECT s.id_stok,
    b.id_barang,
    b.nama_barang,
    b.deskripsi,
    COALESCE(s.stok, 0) AS stok
   FROM stok s
     RIGHT JOIN barang b ON s.id_barang::text = b.id_barang::text;

CREATE TABLE public.approval (
	id_approval varchar NOT NULL,
	id_pengajuan varchar NULL,
	id_karyawan varchar NULL,
	status varchar NULL,
	deskripsi varchar NULL,
	CONSTRAINT approval_pkey PRIMARY KEY (id_approval)
);

create sequence approval_seq start 1;

    
CREATE TABLE public.pengajuan_barang (
	id_pengajuan varchar NOT NULL,
	nama_pengajuan varchar NULL,
	id_barang varchar NULL,
	id_karyawan varchar NULL,
	kuantitas int4 NULL,
	kuantitas_approval int4 NULL,
	deskripsi varchar NULL,
	CONSTRAINT pengajuan_barang_pkey PRIMARY KEY (id_pengajuan)
);

create sequence pengajuan_barang_seq start 1;

CREATE OR REPLACE VIEW public.v_pengajuan_barang
AS SELECT pb.id_pengajuan,
    pb.nama_pengajuan,
    pb.id_barang,
    b.nama_barang,
    pb.id_karyawan,
    k.nama_karyawan,
    ''::text AS jabatan,
    pb.kuantitas,
    pb.kuantitas_approval,
    pb.deskripsi,
    COALESCE(a.status, 'OPEN'::character varying) AS status
   FROM pengajuan_barang pb
     LEFT JOIN karyawan k ON pb.id_karyawan::text = k.id_karyawan::text
     JOIN barang b ON pb.id_barang::text = b.id_barang::text
     LEFT JOIN approval a ON pb.id_pengajuan::text = a.id_pengajuan::text;
    

CREATE OR REPLACE FUNCTION public.f_update_stock_barang()
 RETURNS trigger
 LANGUAGE plpgsql
AS $function$
declare
v_qty integer;
v_id_barang varchar;
begin
	
  select kuantitas, id_barang
  	into v_qty, v_id_barang
  	from pengajuan_barang
  	where id_pengajuan = new.id_pengajuan;
  
  -- For an INSERT operation, add the new quantity to the stock
  IF TG_OP = 'INSERT' then
    UPDATE stok 
    SET stok = stok - v_qty
    WHERE id_barang = v_id_barang;
  -- For an UPDATE operation, calculate the difference and update the stock
  ELSIF TG_OP = 'UPDATE' THEN
   
  select kuantitas, id_barang
  	into v_qty, v_id_barang
  	from pengajuan_barang
  	where id_pengajuan = new.id_pengajuan;
  	
  	if new.status = 'REJECTED' then
		UPDATE stok 
	    SET stok = stok + v_qty
	    WHERE id_barang = v_id_barang;
  	end if;
  END IF;
  RETURN NEW;
END;
$function$
;


create trigger trg_update_stock_on_approval after
insert
    or
update
    on
    public.approval for each row execute procedure f_update_stock_barang();


CREATE OR REPLACE FUNCTION public.insert_id_role()
 RETURNS trigger
 LANGUAGE plpgsql
AS $function$
BEGIN
  -- For an INSERT operation, add the new quantity to the stock
  IF TG_OP = 'INSERT' THEN
    UPDATE users u
    SET id_role = (select id_role from karyawan where email = u.email)
    WHERE id = new.id;
  END IF;
  RETURN NEW;
END;
$function$
;


create trigger trg_insert_id_role after
insert
    on
    public.users for each row execute procedure insert_id_role()

CREATE OR REPLACE FUNCTION public.f_gen_id(i_id_name character varying, i_seq character varying)
 RETURNS character varying
 LANGUAGE plpgsql
AS $function$ 
declare
/*
 * fungsi untuk membuat id dari pola id yang tersimpan di global_param 
 * return : pola dengan penambahan sequence 
 */
v_return_id VARCHAR;
v_placeholder VARCHAR;
v_code varchar;
v_date varchar;
v_seq varchar;
BEGIN
	SELECT VALUE INTO v_placeholder
	FROM global_param 
	WHERE NAME = i_id_name;

v_code = split_part(v_placeholder, '-', 1);
v_date = replace(split_part(v_placeholder, '-', 2), 'YYYYMM', to_char(current_date, 'YYYYMM'));
v_seq := lpad(to_char(nextval(i_seq), 'FM99999'), length(split_part(v_placeholder, '-', 3)), '0');
v_return_id = v_code || '-' || v_date || '-' || v_seq;

RETURN v_return_id;

	EXCEPTION
		WHEN NO_DATA_FOUND THEN
			RAISE EXCEPTION 'No data found for %', i_id_name;
		WHEN others then
			return null;
END;
$function$
;


create sequence karyawan_seq start 1;

CREATE TABLE public.karyawan (
	id_karyawan text NULL,
	nama_karyawan text NULL,
	alamat text NULL,
	email text NULL,
	id_role varchar NULL
);

CREATE OR REPLACE FUNCTION public.update_user_on_update_karyawan()
 RETURNS trigger
 LANGUAGE plpgsql
AS $function$
BEGIN
  -- For an INSERT operation, add the new quantity to the stock
  IF TG_OP = 'DELETE' THEN
    delete from users 
    where email = old.email;
  -- For an UPDATE operation, calculate the difference and update the stock
  ELSIF TG_OP = 'UPDATE' THEN
    UPDATE users
    SET email = new.email,
    id_role = new.id_role
    WHERE email = old.email;
  END IF;
  RETURN NEW;
END;
$function$
;


create trigger trg_approval_update_stock_qty after
delete
    or
update
    on
    public.karyawan for each row execute procedure update_user_on_update_karyawan();