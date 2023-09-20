--
-- PostgreSQL database dump
--

-- Dumped from database version 10.23
-- Dumped by pg_dump version 10.23

-- Started on 2023-09-20 20:34:23

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 267 (class 1255 OID 40988)
-- Name: f_gen_id(character varying); Type: FUNCTION; Schema: public; Owner: db_cuti
--

CREATE FUNCTION public.f_gen_id(i_id_name character varying) RETURNS character varying
    LANGUAGE plpgsql
    AS $$ 
DECLARE
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
v_seq := lpad(to_char(nextval('karyawan_seq'), 'FM99999'), length(split_part(v_placeholder, 'YYYYMM', 2)), '0');
v_return_id = v_code || '-' || v_date || v_seq;

RETURN v_return_id;

	EXCEPTION
		WHEN NO_DATA_FOUND THEN
			RAISE EXCEPTION 'No data found for %', i_id_name;
		WHEN others then
			return null;
END;
$$;


ALTER FUNCTION public.f_gen_id(i_id_name character varying) OWNER TO db_cuti;

--
-- TOC entry 266 (class 1255 OID 40989)
-- Name: f_gen_id(character varying, character varying); Type: FUNCTION; Schema: public; Owner: db_cuti
--

CREATE FUNCTION public.f_gen_id(i_id_name character varying, i_seq character varying) RETURNS character varying
    LANGUAGE plpgsql
    AS $$ 
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
$$;


ALTER FUNCTION public.f_gen_id(i_id_name character varying, i_seq character varying) OWNER TO db_cuti;

--
-- TOC entry 253 (class 1255 OID 24669)
-- Name: f_get_param_text(integer); Type: FUNCTION; Schema: public; Owner: db_cuti
--

CREATE FUNCTION public.f_get_param_text(i_param_value_id integer) RETURNS character varying
    LANGUAGE plpgsql
    AS $$
DECLARE
v_param_text varchar;
BEGIN
	select parameter_text 
		into v_param_text
	from parameter_value
	where id = i_param_value_id
order by id desc ;

return v_param_text;
END;
$$;


ALTER FUNCTION public.f_get_param_text(i_param_value_id integer) OWNER TO db_cuti;

--
-- TOC entry 268 (class 1255 OID 49219)
-- Name: f_update_stock_barang(); Type: FUNCTION; Schema: public; Owner: db_cuti
--

CREATE FUNCTION public.f_update_stock_barang() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
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
$$;


ALTER FUNCTION public.f_update_stock_barang() OWNER TO db_cuti;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 239 (class 1259 OID 49197)
-- Name: approval; Type: TABLE; Schema: public; Owner: db_cuti
--

CREATE TABLE public.approval (
    id_approval character varying NOT NULL,
    id_pengajuan character varying,
    id_karyawan character varying,
    status character varying,
    deskripsi character varying
);


ALTER TABLE public.approval OWNER TO db_cuti;

--
-- TOC entry 241 (class 1259 OID 49215)
-- Name: approval_seq; Type: SEQUENCE; Schema: public; Owner: db_cuti
--

CREATE SEQUENCE public.approval_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.approval_seq OWNER TO db_cuti;

--
-- TOC entry 228 (class 1259 OID 40972)
-- Name: barang; Type: TABLE; Schema: public; Owner: db_cuti
--

CREATE TABLE public.barang (
    id_barang character varying NOT NULL,
    nama_barang character varying,
    deskripsi character varying
);


ALTER TABLE public.barang OWNER TO db_cuti;

--
-- TOC entry 231 (class 1259 OID 40986)
-- Name: barang_seq; Type: SEQUENCE; Schema: public; Owner: db_cuti
--

CREATE SEQUENCE public.barang_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.barang_seq OWNER TO db_cuti;

--
-- TOC entry 232 (class 1259 OID 40994)
-- Name: barang_seq2; Type: SEQUENCE; Schema: public; Owner: db_cuti
--

CREATE SEQUENCE public.barang_seq2
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.barang_seq2 OWNER TO db_cuti;

--
-- TOC entry 230 (class 1259 OID 40982)
-- Name: global_param; Type: TABLE; Schema: public; Owner: db_cuti
--

CREATE TABLE public.global_param (
    id integer NOT NULL,
    name character varying(100),
    data_type character varying(10),
    value character varying(255)
);


ALTER TABLE public.global_param OWNER TO db_cuti;

--
-- TOC entry 229 (class 1259 OID 40980)
-- Name: global_param_id_seq; Type: SEQUENCE; Schema: public; Owner: db_cuti
--

CREATE SEQUENCE public.global_param_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.global_param_id_seq OWNER TO db_cuti;

--
-- TOC entry 3054 (class 0 OID 0)
-- Dependencies: 229
-- Name: global_param_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: db_cuti
--

ALTER SEQUENCE public.global_param_id_seq OWNED BY public.global_param.id;


--
-- TOC entry 226 (class 1259 OID 40960)
-- Name: karyawan; Type: TABLE; Schema: public; Owner: db_cuti
--

CREATE TABLE public.karyawan (
    id_karyawan character varying NOT NULL,
    nama_karyawan character varying,
    alamat character varying,
    email character varying
);


ALTER TABLE public.karyawan OWNER TO db_cuti;

--
-- TOC entry 227 (class 1259 OID 40968)
-- Name: karyawan_seq; Type: SEQUENCE; Schema: public; Owner: db_cuti
--

CREATE SEQUENCE public.karyawan_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.karyawan_seq OWNER TO db_cuti;

--
-- TOC entry 200 (class 1259 OID 24586)
-- Name: members; Type: TABLE; Schema: public; Owner: db_cuti
--

CREATE TABLE public.members (
    id integer NOT NULL,
    name character varying(100)
);


ALTER TABLE public.members OWNER TO db_cuti;

--
-- TOC entry 199 (class 1259 OID 24584)
-- Name: members_id_seq; Type: SEQUENCE; Schema: public; Owner: db_cuti
--

CREATE SEQUENCE public.members_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.members_id_seq OWNER TO db_cuti;

--
-- TOC entry 3055 (class 0 OID 0)
-- Dependencies: 199
-- Name: members_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: db_cuti
--

ALTER SEQUENCE public.members_id_seq OWNED BY public.members.id;


--
-- TOC entry 210 (class 1259 OID 24645)
-- Name: parameter; Type: TABLE; Schema: public; Owner: db_cuti
--

CREATE TABLE public.parameter (
    id integer NOT NULL,
    name character varying(100),
    description character varying(255)
);


ALTER TABLE public.parameter OWNER TO db_cuti;

--
-- TOC entry 209 (class 1259 OID 24643)
-- Name: parameter_id_seq; Type: SEQUENCE; Schema: public; Owner: db_cuti
--

CREATE SEQUENCE public.parameter_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.parameter_id_seq OWNER TO db_cuti;

--
-- TOC entry 3056 (class 0 OID 0)
-- Dependencies: 209
-- Name: parameter_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: db_cuti
--

ALTER SEQUENCE public.parameter_id_seq OWNED BY public.parameter.id;


--
-- TOC entry 212 (class 1259 OID 24653)
-- Name: parameter_value; Type: TABLE; Schema: public; Owner: db_cuti
--

CREATE TABLE public.parameter_value (
    id integer NOT NULL,
    parameter_id integer,
    data_type character varying(10),
    parameter_value character varying(500),
    parameter_text character varying(500)
);


ALTER TABLE public.parameter_value OWNER TO db_cuti;

--
-- TOC entry 211 (class 1259 OID 24651)
-- Name: parameter_value_id_seq; Type: SEQUENCE; Schema: public; Owner: db_cuti
--

CREATE SEQUENCE public.parameter_value_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.parameter_value_id_seq OWNER TO db_cuti;

--
-- TOC entry 3057 (class 0 OID 0)
-- Dependencies: 211
-- Name: parameter_value_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: db_cuti
--

ALTER SEQUENCE public.parameter_value_id_seq OWNED BY public.parameter_value.id;


--
-- TOC entry 237 (class 1259 OID 49181)
-- Name: pengajuan_barang; Type: TABLE; Schema: public; Owner: db_cuti
--

CREATE TABLE public.pengajuan_barang (
    id_pengajuan character varying NOT NULL,
    nama_pengajuan character varying,
    id_barang character varying,
    id_karyawan character varying,
    kuantitas integer,
    kuantitas_approval integer,
    deskripsi character varying
);


ALTER TABLE public.pengajuan_barang OWNER TO db_cuti;

--
-- TOC entry 236 (class 1259 OID 49179)
-- Name: pengajuan_barang_seq; Type: SEQUENCE; Schema: public; Owner: db_cuti
--

CREATE SEQUENCE public.pengajuan_barang_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.pengajuan_barang_seq OWNER TO db_cuti;

--
-- TOC entry 224 (class 1259 OID 24738)
-- Name: pengajuan_cuti; Type: TABLE; Schema: public; Owner: db_cuti
--

CREATE TABLE public.pengajuan_cuti (
    id integer NOT NULL,
    user_id integer,
    start_date date,
    end_date date,
    status character varying(100),
    description character varying(100),
    created_by integer,
    created_date timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_by integer,
    updated_date timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.pengajuan_cuti OWNER TO db_cuti;

--
-- TOC entry 223 (class 1259 OID 24736)
-- Name: pengajuan_cuti_id_seq; Type: SEQUENCE; Schema: public; Owner: db_cuti
--

CREATE SEQUENCE public.pengajuan_cuti_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.pengajuan_cuti_id_seq OWNER TO db_cuti;

--
-- TOC entry 3058 (class 0 OID 0)
-- Dependencies: 223
-- Name: pengajuan_cuti_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: db_cuti
--

ALTER SEQUENCE public.pengajuan_cuti_id_seq OWNED BY public.pengajuan_cuti.id;


--
-- TOC entry 217 (class 1259 OID 24700)
-- Name: permissions; Type: TABLE; Schema: public; Owner: db_cuti
--

CREATE TABLE public.permissions (
    id integer NOT NULL,
    name character varying(100),
    description character varying(255)
);


ALTER TABLE public.permissions OWNER TO db_cuti;

--
-- TOC entry 216 (class 1259 OID 24698)
-- Name: permissions_id_seq; Type: SEQUENCE; Schema: public; Owner: db_cuti
--

CREATE SEQUENCE public.permissions_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.permissions_id_seq OWNER TO db_cuti;

--
-- TOC entry 3059 (class 0 OID 0)
-- Dependencies: 216
-- Name: permissions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: db_cuti
--

ALTER SEQUENCE public.permissions_id_seq OWNED BY public.permissions.id;


--
-- TOC entry 202 (class 1259 OID 24596)
-- Name: product_price; Type: TABLE; Schema: public; Owner: db_cuti
--

CREATE TABLE public.product_price (
    id integer NOT NULL,
    product_id integer,
    member_id integer,
    price numeric(100,0)
);


ALTER TABLE public.product_price OWNER TO db_cuti;

--
-- TOC entry 201 (class 1259 OID 24594)
-- Name: product_price_id_seq; Type: SEQUENCE; Schema: public; Owner: db_cuti
--

CREATE SEQUENCE public.product_price_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.product_price_id_seq OWNER TO db_cuti;

--
-- TOC entry 3060 (class 0 OID 0)
-- Dependencies: 201
-- Name: product_price_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: db_cuti
--

ALTER SEQUENCE public.product_price_id_seq OWNED BY public.product_price.id;


--
-- TOC entry 204 (class 1259 OID 24604)
-- Name: products; Type: TABLE; Schema: public; Owner: db_cuti
--

CREATE TABLE public.products (
    id integer NOT NULL,
    name character varying(100),
    description character varying(255)
);


ALTER TABLE public.products OWNER TO db_cuti;

--
-- TOC entry 203 (class 1259 OID 24602)
-- Name: products_id_seq; Type: SEQUENCE; Schema: public; Owner: db_cuti
--

CREATE SEQUENCE public.products_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.products_id_seq OWNER TO db_cuti;

--
-- TOC entry 3061 (class 0 OID 0)
-- Dependencies: 203
-- Name: products_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: db_cuti
--

ALTER SEQUENCE public.products_id_seq OWNED BY public.products.id;


--
-- TOC entry 238 (class 1259 OID 49189)
-- Name: role; Type: TABLE; Schema: public; Owner: db_cuti
--

CREATE TABLE public.role (
    id_role integer NOT NULL,
    nama_role character varying
);


ALTER TABLE public.role OWNER TO db_cuti;

--
-- TOC entry 219 (class 1259 OID 24708)
-- Name: role_permission; Type: TABLE; Schema: public; Owner: db_cuti
--

CREATE TABLE public.role_permission (
    id integer NOT NULL,
    role_id integer,
    permission_id integer
);


ALTER TABLE public.role_permission OWNER TO db_cuti;

--
-- TOC entry 218 (class 1259 OID 24706)
-- Name: role_permission_id_seq; Type: SEQUENCE; Schema: public; Owner: db_cuti
--

CREATE SEQUENCE public.role_permission_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.role_permission_id_seq OWNER TO db_cuti;

--
-- TOC entry 3062 (class 0 OID 0)
-- Dependencies: 218
-- Name: role_permission_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: db_cuti
--

ALTER SEQUENCE public.role_permission_id_seq OWNED BY public.role_permission.id;


--
-- TOC entry 221 (class 1259 OID 24725)
-- Name: roles; Type: TABLE; Schema: public; Owner: db_cuti
--

CREATE TABLE public.roles (
    id integer NOT NULL,
    name character varying(100),
    role_level integer,
    description character varying(255)
);


ALTER TABLE public.roles OWNER TO db_cuti;

--
-- TOC entry 220 (class 1259 OID 24723)
-- Name: roles_id_seq; Type: SEQUENCE; Schema: public; Owner: db_cuti
--

CREATE SEQUENCE public.roles_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.roles_id_seq OWNER TO db_cuti;

--
-- TOC entry 3063 (class 0 OID 0)
-- Dependencies: 220
-- Name: roles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: db_cuti
--

ALTER SEQUENCE public.roles_id_seq OWNED BY public.roles.id;


--
-- TOC entry 234 (class 1259 OID 49154)
-- Name: stok; Type: TABLE; Schema: public; Owner: db_cuti
--

CREATE TABLE public.stok (
    id_stok integer NOT NULL,
    id_barang character varying,
    stok integer
);


ALTER TABLE public.stok OWNER TO db_cuti;

--
-- TOC entry 233 (class 1259 OID 49152)
-- Name: stok_id_stok_seq; Type: SEQUENCE; Schema: public; Owner: db_cuti
--

CREATE SEQUENCE public.stok_id_stok_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.stok_id_stok_seq OWNER TO db_cuti;

--
-- TOC entry 3064 (class 0 OID 0)
-- Dependencies: 233
-- Name: stok_id_stok_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: db_cuti
--

ALTER SEQUENCE public.stok_id_stok_seq OWNED BY public.stok.id_stok;


--
-- TOC entry 215 (class 1259 OID 24692)
-- Name: user_role; Type: TABLE; Schema: public; Owner: db_cuti
--

CREATE TABLE public.user_role (
    id integer NOT NULL,
    user_id integer,
    role_id integer
);


ALTER TABLE public.user_role OWNER TO db_cuti;

--
-- TOC entry 214 (class 1259 OID 24690)
-- Name: user_role_id_seq; Type: SEQUENCE; Schema: public; Owner: db_cuti
--

CREATE SEQUENCE public.user_role_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.user_role_id_seq OWNER TO db_cuti;

--
-- TOC entry 3065 (class 0 OID 0)
-- Dependencies: 214
-- Name: user_role_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: db_cuti
--

ALTER SEQUENCE public.user_role_id_seq OWNED BY public.user_role.id;


--
-- TOC entry 198 (class 1259 OID 16433)
-- Name: users; Type: TABLE; Schema: public; Owner: db_cuti
--

CREATE TABLE public.users (
    id integer NOT NULL,
    email character varying(100),
    name character varying(255),
    password character varying(500),
    status integer,
    last_login timestamp without time zone,
    created_by character varying(100),
    created_date timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_by character varying(100),
    updated_date timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.users OWNER TO db_cuti;

--
-- TOC entry 197 (class 1259 OID 16431)
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: db_cuti
--

CREATE SEQUENCE public.users_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.users_id_seq OWNER TO db_cuti;

--
-- TOC entry 3066 (class 0 OID 0)
-- Dependencies: 197
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: db_cuti
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- TOC entry 240 (class 1259 OID 49210)
-- Name: v_pengajuan_barang; Type: VIEW; Schema: public; Owner: db_cuti
--

CREATE VIEW public.v_pengajuan_barang AS
 SELECT pb.id_pengajuan,
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
   FROM (((public.pengajuan_barang pb
     LEFT JOIN public.karyawan k ON (((pb.id_karyawan)::text = (k.id_karyawan)::text)))
     JOIN public.barang b ON (((pb.id_barang)::text = (b.id_barang)::text)))
     LEFT JOIN public.approval a ON (((pb.id_pengajuan)::text = (a.id_pengajuan)::text)));


ALTER TABLE public.v_pengajuan_barang OWNER TO db_cuti;

--
-- TOC entry 222 (class 1259 OID 24731)
-- Name: v_user_role; Type: VIEW; Schema: public; Owner: db_cuti
--

CREATE VIEW public.v_user_role AS
 SELECT ur.id,
    ur.user_id,
    ur.role_id,
    u.name AS user_name,
    u.email,
    r.name AS role_name,
    r.description,
    r.role_level
   FROM ((public.user_role ur
     JOIN public.users u ON ((ur.user_id = u.id)))
     JOIN public.roles r ON ((ur.role_id = r.id)))
  ORDER BY ur.user_id;


ALTER TABLE public.v_user_role OWNER TO db_cuti;

--
-- TOC entry 225 (class 1259 OID 32775)
-- Name: v_pengajuan_cuti; Type: VIEW; Schema: public; Owner: db_cuti
--

CREATE VIEW public.v_pengajuan_cuti AS
 SELECT pc.id,
    pc.user_id,
    pc.start_date,
    pc.end_date,
    pc.status,
    pc.description,
    pc.created_by,
    date(pc.created_date) AS created_date,
    pc.updated_by,
    pc.updated_date,
    (pc.end_date - pc.start_date) AS total_days,
    vur.user_name,
    vur.role_name
   FROM (public.pengajuan_cuti pc
     JOIN public.v_user_role vur ON ((pc.created_by = vur.user_id)));


ALTER TABLE public.v_pengajuan_cuti OWNER TO db_cuti;

--
-- TOC entry 208 (class 1259 OID 24635)
-- Name: v_product_price; Type: VIEW; Schema: public; Owner: db_cuti
--

CREATE VIEW public.v_product_price AS
 SELECT pp.id,
    pp.product_id,
    p.name AS product_name,
    pp.member_id,
    m.name AS member_type,
    pp.price
   FROM ((public.product_price pp
     JOIN public.products p ON ((pp.product_id = p.id)))
     JOIN public.members m ON ((pp.member_id = m.id)))
  ORDER BY pp.product_id, pp.member_id;


ALTER TABLE public.v_product_price OWNER TO db_cuti;

--
-- TOC entry 235 (class 1259 OID 49167)
-- Name: v_stock; Type: VIEW; Schema: public; Owner: db_cuti
--

CREATE VIEW public.v_stock AS
 SELECT s.id_stok,
    b.id_barang,
    b.nama_barang,
    b.deskripsi,
    COALESCE(s.stok, 0) AS stok
   FROM (public.stok s
     RIGHT JOIN public.barang b ON (((s.id_barang)::text = (b.id_barang)::text)));


ALTER TABLE public.v_stock OWNER TO db_cuti;

--
-- TOC entry 213 (class 1259 OID 24678)
-- Name: v_user; Type: VIEW; Schema: public; Owner: db_cuti
--

CREATE VIEW public.v_user AS
 SELECT users.id,
    users.email,
    users.name,
    users.status,
        CASE
            WHEN (users.status = 1) THEN 'Active'::text
            WHEN (users.status = 0) THEN 'Inactive'::text
            WHEN (users.id = 2) THEN 'Blocked'::text
            ELSE 'Terminated'::text
        END AS status_txt,
    users.last_login,
    users.created_by,
    users.created_date,
    users.updated_by,
    users.updated_date
   FROM public.users;


ALTER TABLE public.v_user OWNER TO db_cuti;

--
-- TOC entry 2847 (class 2604 OID 40985)
-- Name: global_param id; Type: DEFAULT; Schema: public; Owner: db_cuti
--

ALTER TABLE ONLY public.global_param ALTER COLUMN id SET DEFAULT nextval('public.global_param_id_seq'::regclass);


--
-- TOC entry 2835 (class 2604 OID 24589)
-- Name: members id; Type: DEFAULT; Schema: public; Owner: db_cuti
--

ALTER TABLE ONLY public.members ALTER COLUMN id SET DEFAULT nextval('public.members_id_seq'::regclass);


--
-- TOC entry 2838 (class 2604 OID 24648)
-- Name: parameter id; Type: DEFAULT; Schema: public; Owner: db_cuti
--

ALTER TABLE ONLY public.parameter ALTER COLUMN id SET DEFAULT nextval('public.parameter_id_seq'::regclass);


--
-- TOC entry 2839 (class 2604 OID 24656)
-- Name: parameter_value id; Type: DEFAULT; Schema: public; Owner: db_cuti
--

ALTER TABLE ONLY public.parameter_value ALTER COLUMN id SET DEFAULT nextval('public.parameter_value_id_seq'::regclass);


--
-- TOC entry 2844 (class 2604 OID 24741)
-- Name: pengajuan_cuti id; Type: DEFAULT; Schema: public; Owner: db_cuti
--

ALTER TABLE ONLY public.pengajuan_cuti ALTER COLUMN id SET DEFAULT nextval('public.pengajuan_cuti_id_seq'::regclass);


--
-- TOC entry 2841 (class 2604 OID 24703)
-- Name: permissions id; Type: DEFAULT; Schema: public; Owner: db_cuti
--

ALTER TABLE ONLY public.permissions ALTER COLUMN id SET DEFAULT nextval('public.permissions_id_seq'::regclass);


--
-- TOC entry 2836 (class 2604 OID 24599)
-- Name: product_price id; Type: DEFAULT; Schema: public; Owner: db_cuti
--

ALTER TABLE ONLY public.product_price ALTER COLUMN id SET DEFAULT nextval('public.product_price_id_seq'::regclass);


--
-- TOC entry 2837 (class 2604 OID 24607)
-- Name: products id; Type: DEFAULT; Schema: public; Owner: db_cuti
--

ALTER TABLE ONLY public.products ALTER COLUMN id SET DEFAULT nextval('public.products_id_seq'::regclass);


--
-- TOC entry 2842 (class 2604 OID 24711)
-- Name: role_permission id; Type: DEFAULT; Schema: public; Owner: db_cuti
--

ALTER TABLE ONLY public.role_permission ALTER COLUMN id SET DEFAULT nextval('public.role_permission_id_seq'::regclass);


--
-- TOC entry 2843 (class 2604 OID 24728)
-- Name: roles id; Type: DEFAULT; Schema: public; Owner: db_cuti
--

ALTER TABLE ONLY public.roles ALTER COLUMN id SET DEFAULT nextval('public.roles_id_seq'::regclass);


--
-- TOC entry 2848 (class 2604 OID 49157)
-- Name: stok id_stok; Type: DEFAULT; Schema: public; Owner: db_cuti
--

ALTER TABLE ONLY public.stok ALTER COLUMN id_stok SET DEFAULT nextval('public.stok_id_stok_seq'::regclass);


--
-- TOC entry 2840 (class 2604 OID 24695)
-- Name: user_role id; Type: DEFAULT; Schema: public; Owner: db_cuti
--

ALTER TABLE ONLY public.user_role ALTER COLUMN id SET DEFAULT nextval('public.user_role_id_seq'::regclass);


--
-- TOC entry 2832 (class 2604 OID 16436)
-- Name: users id; Type: DEFAULT; Schema: public; Owner: db_cuti
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- TOC entry 3046 (class 0 OID 49197)
-- Dependencies: 239
-- Data for Name: approval; Type: TABLE DATA; Schema: public; Owner: db_cuti
--

INSERT INTO public.approval VALUES ('APR-202309-00004', 'PBR-202309-00003', NULL, 'REJECTED', NULL);


--
-- TOC entry 3036 (class 0 OID 40972)
-- Dependencies: 228
-- Data for Name: barang; Type: TABLE DATA; Schema: public; Owner: db_cuti
--

INSERT INTO public.barang VALUES ('BRG-202309-00001', 'Laptop Karyawan', 'Laptop Karyawan');
INSERT INTO public.barang VALUES ('BRG-202309-00008', 'Kursi Karyawan', 'Kursi Karyawan');
INSERT INTO public.barang VALUES ('BRG-202309-00009', 'Meja Kayu', 'Meja Kayu');
INSERT INTO public.barang VALUES ('BRG-202309-00010', 'Laptop Asus', 'Laptop Asus');


--
-- TOC entry 3038 (class 0 OID 40982)
-- Dependencies: 230
-- Data for Name: global_param; Type: TABLE DATA; Schema: public; Owner: db_cuti
--

INSERT INTO public.global_param VALUES (1, 'PLACEHOLDER_ID_KARYAWAN', 'str', 'KYR-YYYYMM-00000');
INSERT INTO public.global_param VALUES (2, 'PLACEHOLDER_ID_BARANG', 'str', 'BRG-YYYYMM-00000');
INSERT INTO public.global_param VALUES (3, 'PLACEHOLDER_ID_PENGAJUAN_BARANG', 'str', 'PBR-YYYYMM-00000');
INSERT INTO public.global_param VALUES (4, 'PLACEHOLDER_ID_APPROVAL', 'str', 'APR-YYYYMM-00000');


--
-- TOC entry 3034 (class 0 OID 40960)
-- Dependencies: 226
-- Data for Name: karyawan; Type: TABLE DATA; Schema: public; Owner: db_cuti
--

INSERT INTO public.karyawan VALUES ('KYR-202309-00002', 'Budi Setiawan', 'Cibinong', 'budi.s@gmail.com');
INSERT INTO public.karyawan VALUES ('KYR-202309-00005', 'Mario Tegar', 'Ciawi', 'mario.t@gmail.com');
INSERT INTO public.karyawan VALUES ('KYR-202309-00006', 'Brian Smith', 'Cibinong', 'brian.fox@gmail.com');


--
-- TOC entry 3015 (class 0 OID 24586)
-- Dependencies: 200
-- Data for Name: members; Type: TABLE DATA; Schema: public; Owner: db_cuti
--

INSERT INTO public.members VALUES (1, 'non-member');
INSERT INTO public.members VALUES (2, 'member level 1');
INSERT INTO public.members VALUES (3, 'member level 2');
INSERT INTO public.members VALUES (4, 'member level 3');


--
-- TOC entry 3021 (class 0 OID 24645)
-- Dependencies: 210
-- Data for Name: parameter; Type: TABLE DATA; Schema: public; Owner: db_cuti
--

INSERT INTO public.parameter VALUES (1, 'USER_STATUS', 'User Status');


--
-- TOC entry 3023 (class 0 OID 24653)
-- Dependencies: 212
-- Data for Name: parameter_value; Type: TABLE DATA; Schema: public; Owner: db_cuti
--

INSERT INTO public.parameter_value VALUES (3, 1, 'int', '1', 'Active');
INSERT INTO public.parameter_value VALUES (4, 1, 'int', '0', 'Inactive');
INSERT INTO public.parameter_value VALUES (5, 1, 'int', '2', 'Blocked');
INSERT INTO public.parameter_value VALUES (6, 1, 'int', '3', 'Terminated');


--
-- TOC entry 3044 (class 0 OID 49181)
-- Dependencies: 237
-- Data for Name: pengajuan_barang; Type: TABLE DATA; Schema: public; Owner: db_cuti
--

INSERT INTO public.pengajuan_barang VALUES ('PBR-202309-00003', 'Pengajuan laptop', 'BRG-202309-00001', NULL, 50, NULL, 'Pengajuan laptop untuk csr');
INSERT INTO public.pengajuan_barang VALUES ('PBR-202309-00004', 'Pengadaan Meja', 'BRG-202309-00009', NULL, 10, NULL, 'Pengadaan Meja Kayu');


--
-- TOC entry 3033 (class 0 OID 24738)
-- Dependencies: 224
-- Data for Name: pengajuan_cuti; Type: TABLE DATA; Schema: public; Owner: db_cuti
--

INSERT INTO public.pengajuan_cuti VALUES (3, 2, '2023-09-20', '2023-09-29', 'DRAFT', 'test cuti manager ', 2, '2023-09-15 15:17:13.611173', 2, '2023-09-15 15:17:13.611173');
INSERT INTO public.pengajuan_cuti VALUES (4, 3, '2023-09-20', '2023-09-22', 'DRAFT', 'test cuti spv', 3, '2023-09-15 15:18:15.113693', 3, '2023-09-15 15:18:15.113693');
INSERT INTO public.pengajuan_cuti VALUES (2, 5, '2023-09-18', '2023-09-20', 'APPROVED', 'test', 5, '2023-09-15 14:50:45.01911', 5, '2023-09-15 14:50:45.01911');
INSERT INTO public.pengajuan_cuti VALUES (5, 1, '2023-09-25', '2023-09-27', 'DRAFT', 'cuti staff 1', 1, '2023-09-18 15:39:08.7369', 1, '2023-09-18 15:39:08.7369');
INSERT INTO public.pengajuan_cuti VALUES (6, 1, '2023-09-04', '2023-09-06', 'DRAFT', 'cuti staff 1', 1, '2023-09-18 15:39:33.148686', 1, '2023-09-18 15:39:33.148686');
INSERT INTO public.pengajuan_cuti VALUES (7, 7, '2023-09-12', '2023-09-15', 'DRAFT', 'cuti spv 2', 7, '2023-09-18 15:49:36.537026', 7, '2023-09-18 15:49:36.537026');
INSERT INTO public.pengajuan_cuti VALUES (8, 7, '2023-09-19', '2023-09-22', 'DRAFT', 'cuti spv 2', 7, '2023-09-18 15:49:56.468515', 7, '2023-09-18 15:49:56.468515');
INSERT INTO public.pengajuan_cuti VALUES (9, 8, '2023-09-04', '2023-09-08', 'DRAFT', 'cuti spv 3', 8, '2023-09-18 15:50:41.363424', 8, '2023-09-18 15:50:41.363424');
INSERT INTO public.pengajuan_cuti VALUES (11, 6, '2023-09-06', '2023-09-07', 'DRAFT', 'cuti staff 3', 6, '2023-09-18 15:53:08.853405', 6, '2023-09-18 15:53:08.853405');
INSERT INTO public.pengajuan_cuti VALUES (12, 6, '2023-09-13', '2023-09-14', 'DRAFT', 'cuti staff 3', 6, '2023-09-18 15:53:19.399995', 6, '2023-09-18 15:53:19.399995');
INSERT INTO public.pengajuan_cuti VALUES (13, 6, '2023-09-20', '2023-09-22', 'DRAFT', 'cuti staff 3', 6, '2023-09-18 15:53:30.272859', 6, '2023-09-18 15:53:30.272859');


--
-- TOC entry 3027 (class 0 OID 24700)
-- Dependencies: 217
-- Data for Name: permissions; Type: TABLE DATA; Schema: public; Owner: db_cuti
--

INSERT INTO public.permissions VALUES (1, 'view-user-data', 'View user data permission');
INSERT INTO public.permissions VALUES (2, 'view-pengajuan-cuti-data', 'view-pengajuan-cuti-data permission');


--
-- TOC entry 3017 (class 0 OID 24596)
-- Dependencies: 202
-- Data for Name: product_price; Type: TABLE DATA; Schema: public; Owner: db_cuti
--

INSERT INTO public.product_price VALUES (10, 1, 1, 2000);
INSERT INTO public.product_price VALUES (12, 1, 3, 1500);
INSERT INTO public.product_price VALUES (15, 2, 3, 2500);
INSERT INTO public.product_price VALUES (17, 3, 2, 3800);
INSERT INTO public.product_price VALUES (18, 3, 3, 2500);
INSERT INTO public.product_price VALUES (19, 4, 1, 3000);
INSERT INTO public.product_price VALUES (22, 5, 1, 4000);
INSERT INTO public.product_price VALUES (25, 1, 4, 1000);
INSERT INTO public.product_price VALUES (26, 2, 4, 2000);
INSERT INTO public.product_price VALUES (11, 1, 2, 1800);
INSERT INTO public.product_price VALUES (14, 2, 2, 2800);
INSERT INTO public.product_price VALUES (20, 4, 2, 1000);
INSERT INTO public.product_price VALUES (23, 5, 2, 2000);
INSERT INTO public.product_price VALUES (21, 4, 3, 2000);
INSERT INTO public.product_price VALUES (29, 5, 4, 3000);
INSERT INTO public.product_price VALUES (24, 5, 3, 4000);
INSERT INTO public.product_price VALUES (28, 4, 4, 2500);
INSERT INTO public.product_price VALUES (27, 3, 4, 6000);
INSERT INTO public.product_price VALUES (16, 3, 1, 8000);
INSERT INTO public.product_price VALUES (31, 6, 2, 4000);
INSERT INTO public.product_price VALUES (32, 6, 3, 3000);
INSERT INTO public.product_price VALUES (33, 6, 4, 2000);
INSERT INTO public.product_price VALUES (30, 6, 1, 8000);
INSERT INTO public.product_price VALUES (13, 2, 1, 6000);


--
-- TOC entry 3019 (class 0 OID 24604)
-- Dependencies: 204
-- Data for Name: products; Type: TABLE DATA; Schema: public; Owner: db_cuti
--

INSERT INTO public.products VALUES (1, 'apple', '');
INSERT INTO public.products VALUES (2, 'banana', '');
INSERT INTO public.products VALUES (3, 'orange', '');
INSERT INTO public.products VALUES (4, 'test', NULL);
INSERT INTO public.products VALUES (5, 'test2', NULL);
INSERT INTO public.products VALUES (6, 'test3', NULL);


--
-- TOC entry 3045 (class 0 OID 49189)
-- Dependencies: 238
-- Data for Name: role; Type: TABLE DATA; Schema: public; Owner: db_cuti
--



--
-- TOC entry 3029 (class 0 OID 24708)
-- Dependencies: 219
-- Data for Name: role_permission; Type: TABLE DATA; Schema: public; Owner: db_cuti
--



--
-- TOC entry 3031 (class 0 OID 24725)
-- Dependencies: 221
-- Data for Name: roles; Type: TABLE DATA; Schema: public; Owner: db_cuti
--

INSERT INTO public.roles VALUES (1, 'Staff', 1, 'Staff Role');
INSERT INTO public.roles VALUES (3, 'Manager', 3, 'Manager Role');
INSERT INTO public.roles VALUES (5, 'Supervisor', 2, 'Supervisor Role');


--
-- TOC entry 3042 (class 0 OID 49154)
-- Dependencies: 234
-- Data for Name: stok; Type: TABLE DATA; Schema: public; Owner: db_cuti
--

INSERT INTO public.stok VALUES (2, 'BRG-202309-00008', 100);
INSERT INTO public.stok VALUES (3, 'BRG-202309-00009', 100);
INSERT INTO public.stok VALUES (5, 'BRG-202309-00010', 20);
INSERT INTO public.stok VALUES (1, 'BRG-202309-00001', 1000);


--
-- TOC entry 3025 (class 0 OID 24692)
-- Dependencies: 215
-- Data for Name: user_role; Type: TABLE DATA; Schema: public; Owner: db_cuti
--

INSERT INTO public.user_role VALUES (1, 5, 1);
INSERT INTO public.user_role VALUES (3, 3, 5);
INSERT INTO public.user_role VALUES (4, 2, 3);
INSERT INTO public.user_role VALUES (5, 1, 1);
INSERT INTO public.user_role VALUES (6, 6, 1);
INSERT INTO public.user_role VALUES (7, 7, 5);
INSERT INTO public.user_role VALUES (8, 8, 5);


--
-- TOC entry 3013 (class 0 OID 16433)
-- Dependencies: 198
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: db_cuti
--

INSERT INTO public.users VALUES (1, 'user_staff@gmail.com', 'user staff 1', '$2y$10$DywFp6Ny3Yz9TRwwCtVAe.KV4HKWrswuIkxBOUcBi1jLWZXxpod7O', 1, '2023-09-20 16:48:33', 'admin', '2023-09-13 15:13:54.830166', 'admin', '2023-09-13 15:13:54.830166');
INSERT INTO public.users VALUES (4, 'admin@gmail.com', 'administrator', '$2y$10$slFKRfd5GdGd85cJnkckjeORDhSK.9SWQ1jF8htNNCvbigOCzf71O', 1, NULL, 'user staff 1', '2023-09-15 12:57:31.635576', 'user staff 1', '2023-09-15 12:57:31.635576');
INSERT INTO public.users VALUES (5, 'staff2@gmail.com', 'staff2', '$2y$10$AaOhyMt44lY1zc88zl/Z0OopWkJtfKVbfr1769VOJv0IiGICUCYNC', 1, '2023-09-15 14:49:11', 'user staff 1', '2023-09-15 13:12:55.71479', 'user staff 1', '2023-09-15 13:12:55.71479');
INSERT INTO public.users VALUES (3, 'spv@gmail.com', 'supervisor 1', '$2y$10$MPC7qzinyCTbOX4d7xv50O44uKRCw.V6ROG9aH3sK.2IAKwlmnTmG', 1, '2023-09-15 15:46:06', 'user staff 1', '2023-09-15 12:55:52.300553', 'user staff 1', '2023-09-15 12:55:52.300553');
INSERT INTO public.users VALUES (7, 'spv2@gmail.com', 'Supervisor 2', '$2y$10$V09iTQX2rsgeuIq055JiY.wy0pD6SFk.4CPBilFwsmcnD1j8vYTUW', 1, '2023-09-18 15:49:10', 'user staff 1', '2023-09-18 15:37:43.70015', 'user staff 1', '2023-09-18 15:37:43.70015');
INSERT INTO public.users VALUES (8, 'spv3@gmail.com', 'Supervisor 3', '$2y$10$f82AtXy/Mt8AKF6MoNIiouKN28pKEsSsW1vQgBDeEjT4sHW8SLQ8u', 1, '2023-09-18 15:50:19', 'user staff 1', '2023-09-18 15:38:10.808507', 'user staff 1', '2023-09-18 15:38:10.808507');
INSERT INTO public.users VALUES (6, 'staff3@gmail.com', 'staff 3', '$2y$10$bunVGJsbz12gV8IvZhK7eOHaV17JteVDk8yeoE5FpC2QkqqTe1WHC', 1, '2023-09-18 15:52:51', 'user staff 1', '2023-09-18 15:37:03.938247', 'user staff 1', '2023-09-18 15:37:03.938247');
INSERT INTO public.users VALUES (2, 'manager1@gmail.com', 'manager 1', '$2y$10$/Jv8DKlsNl/NdzI2FWi2R.7JHmGOoOK/kRjgyUXDJPSFic67VWWE.', 1, '2023-09-18 16:40:01', 'user staff 1', '2023-09-15 12:54:18.185452', 'user staff 1', '2023-09-15 12:54:18.185452');


--
-- TOC entry 3067 (class 0 OID 0)
-- Dependencies: 241
-- Name: approval_seq; Type: SEQUENCE SET; Schema: public; Owner: db_cuti
--

SELECT pg_catalog.setval('public.approval_seq', 4, true);


--
-- TOC entry 3068 (class 0 OID 0)
-- Dependencies: 231
-- Name: barang_seq; Type: SEQUENCE SET; Schema: public; Owner: db_cuti
--

SELECT pg_catalog.setval('public.barang_seq', 11, true);


--
-- TOC entry 3069 (class 0 OID 0)
-- Dependencies: 232
-- Name: barang_seq2; Type: SEQUENCE SET; Schema: public; Owner: db_cuti
--

SELECT pg_catalog.setval('public.barang_seq2', 5, true);


--
-- TOC entry 3070 (class 0 OID 0)
-- Dependencies: 229
-- Name: global_param_id_seq; Type: SEQUENCE SET; Schema: public; Owner: db_cuti
--

SELECT pg_catalog.setval('public.global_param_id_seq', 4, true);


--
-- TOC entry 3071 (class 0 OID 0)
-- Dependencies: 227
-- Name: karyawan_seq; Type: SEQUENCE SET; Schema: public; Owner: db_cuti
--

SELECT pg_catalog.setval('public.karyawan_seq', 6, true);


--
-- TOC entry 3072 (class 0 OID 0)
-- Dependencies: 199
-- Name: members_id_seq; Type: SEQUENCE SET; Schema: public; Owner: db_cuti
--

SELECT pg_catalog.setval('public.members_id_seq', 4, true);


--
-- TOC entry 3073 (class 0 OID 0)
-- Dependencies: 209
-- Name: parameter_id_seq; Type: SEQUENCE SET; Schema: public; Owner: db_cuti
--

SELECT pg_catalog.setval('public.parameter_id_seq', 1, true);


--
-- TOC entry 3074 (class 0 OID 0)
-- Dependencies: 211
-- Name: parameter_value_id_seq; Type: SEQUENCE SET; Schema: public; Owner: db_cuti
--

SELECT pg_catalog.setval('public.parameter_value_id_seq', 6, true);


--
-- TOC entry 3075 (class 0 OID 0)
-- Dependencies: 236
-- Name: pengajuan_barang_seq; Type: SEQUENCE SET; Schema: public; Owner: db_cuti
--

SELECT pg_catalog.setval('public.pengajuan_barang_seq', 4, true);


--
-- TOC entry 3076 (class 0 OID 0)
-- Dependencies: 223
-- Name: pengajuan_cuti_id_seq; Type: SEQUENCE SET; Schema: public; Owner: db_cuti
--

SELECT pg_catalog.setval('public.pengajuan_cuti_id_seq', 13, true);


--
-- TOC entry 3077 (class 0 OID 0)
-- Dependencies: 216
-- Name: permissions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: db_cuti
--

SELECT pg_catalog.setval('public.permissions_id_seq', 2, true);


--
-- TOC entry 3078 (class 0 OID 0)
-- Dependencies: 201
-- Name: product_price_id_seq; Type: SEQUENCE SET; Schema: public; Owner: db_cuti
--

SELECT pg_catalog.setval('public.product_price_id_seq', 33, true);


--
-- TOC entry 3079 (class 0 OID 0)
-- Dependencies: 203
-- Name: products_id_seq; Type: SEQUENCE SET; Schema: public; Owner: db_cuti
--

SELECT pg_catalog.setval('public.products_id_seq', 6, true);


--
-- TOC entry 3080 (class 0 OID 0)
-- Dependencies: 218
-- Name: role_permission_id_seq; Type: SEQUENCE SET; Schema: public; Owner: db_cuti
--

SELECT pg_catalog.setval('public.role_permission_id_seq', 1, false);


--
-- TOC entry 3081 (class 0 OID 0)
-- Dependencies: 220
-- Name: roles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: db_cuti
--

SELECT pg_catalog.setval('public.roles_id_seq', 5, true);


--
-- TOC entry 3082 (class 0 OID 0)
-- Dependencies: 233
-- Name: stok_id_stok_seq; Type: SEQUENCE SET; Schema: public; Owner: db_cuti
--

SELECT pg_catalog.setval('public.stok_id_stok_seq', 5, true);


--
-- TOC entry 3083 (class 0 OID 0)
-- Dependencies: 214
-- Name: user_role_id_seq; Type: SEQUENCE SET; Schema: public; Owner: db_cuti
--

SELECT pg_catalog.setval('public.user_role_id_seq', 8, true);


--
-- TOC entry 3084 (class 0 OID 0)
-- Dependencies: 197
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: db_cuti
--

SELECT pg_catalog.setval('public.users_id_seq', 8, true);


--
-- TOC entry 2882 (class 2606 OID 49204)
-- Name: approval approval_pkey; Type: CONSTRAINT; Schema: public; Owner: db_cuti
--

ALTER TABLE ONLY public.approval
    ADD CONSTRAINT approval_pkey PRIMARY KEY (id_approval);


--
-- TOC entry 2874 (class 2606 OID 40979)
-- Name: barang barang_pkey; Type: CONSTRAINT; Schema: public; Owner: db_cuti
--

ALTER TABLE ONLY public.barang
    ADD CONSTRAINT barang_pkey PRIMARY KEY (id_barang);


--
-- TOC entry 2872 (class 2606 OID 40967)
-- Name: karyawan karyawan_pkey; Type: CONSTRAINT; Schema: public; Owner: db_cuti
--

ALTER TABLE ONLY public.karyawan
    ADD CONSTRAINT karyawan_pkey PRIMARY KEY (id_karyawan);


--
-- TOC entry 2852 (class 2606 OID 24591)
-- Name: members members_pkey; Type: CONSTRAINT; Schema: public; Owner: db_cuti
--

ALTER TABLE ONLY public.members
    ADD CONSTRAINT members_pkey PRIMARY KEY (id);


--
-- TOC entry 2858 (class 2606 OID 24650)
-- Name: parameter parameter_pkey; Type: CONSTRAINT; Schema: public; Owner: db_cuti
--

ALTER TABLE ONLY public.parameter
    ADD CONSTRAINT parameter_pkey PRIMARY KEY (id);


--
-- TOC entry 2860 (class 2606 OID 24661)
-- Name: parameter_value parameter_value_pkey; Type: CONSTRAINT; Schema: public; Owner: db_cuti
--

ALTER TABLE ONLY public.parameter_value
    ADD CONSTRAINT parameter_value_pkey PRIMARY KEY (id);


--
-- TOC entry 2878 (class 2606 OID 49188)
-- Name: pengajuan_barang pengajuan_barang_pkey; Type: CONSTRAINT; Schema: public; Owner: db_cuti
--

ALTER TABLE ONLY public.pengajuan_barang
    ADD CONSTRAINT pengajuan_barang_pkey PRIMARY KEY (id_pengajuan);


--
-- TOC entry 2870 (class 2606 OID 24745)
-- Name: pengajuan_cuti pengajuan_cuti_pkey; Type: CONSTRAINT; Schema: public; Owner: db_cuti
--

ALTER TABLE ONLY public.pengajuan_cuti
    ADD CONSTRAINT pengajuan_cuti_pkey PRIMARY KEY (id);


--
-- TOC entry 2864 (class 2606 OID 24705)
-- Name: permissions permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: db_cuti
--

ALTER TABLE ONLY public.permissions
    ADD CONSTRAINT permissions_pkey PRIMARY KEY (id);


--
-- TOC entry 2854 (class 2606 OID 24601)
-- Name: product_price product_price_pkey; Type: CONSTRAINT; Schema: public; Owner: db_cuti
--

ALTER TABLE ONLY public.product_price
    ADD CONSTRAINT product_price_pkey PRIMARY KEY (id);


--
-- TOC entry 2856 (class 2606 OID 24609)
-- Name: products products_pkey; Type: CONSTRAINT; Schema: public; Owner: db_cuti
--

ALTER TABLE ONLY public.products
    ADD CONSTRAINT products_pkey PRIMARY KEY (id);


--
-- TOC entry 2866 (class 2606 OID 24713)
-- Name: role_permission role_permission_pkey; Type: CONSTRAINT; Schema: public; Owner: db_cuti
--

ALTER TABLE ONLY public.role_permission
    ADD CONSTRAINT role_permission_pkey PRIMARY KEY (id);


--
-- TOC entry 2880 (class 2606 OID 49196)
-- Name: role role_pkey; Type: CONSTRAINT; Schema: public; Owner: db_cuti
--

ALTER TABLE ONLY public.role
    ADD CONSTRAINT role_pkey PRIMARY KEY (id_role);


--
-- TOC entry 2868 (class 2606 OID 24730)
-- Name: roles roles_pkey; Type: CONSTRAINT; Schema: public; Owner: db_cuti
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (id);


--
-- TOC entry 2876 (class 2606 OID 49162)
-- Name: stok stok_pkey; Type: CONSTRAINT; Schema: public; Owner: db_cuti
--

ALTER TABLE ONLY public.stok
    ADD CONSTRAINT stok_pkey PRIMARY KEY (id_stok);


--
-- TOC entry 2862 (class 2606 OID 24697)
-- Name: user_role user_role_pkey; Type: CONSTRAINT; Schema: public; Owner: db_cuti
--

ALTER TABLE ONLY public.user_role
    ADD CONSTRAINT user_role_pkey PRIMARY KEY (id);


--
-- TOC entry 2850 (class 2606 OID 16443)
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: db_cuti
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- TOC entry 2884 (class 2620 OID 49223)
-- Name: approval trg_update_stock_on_approval; Type: TRIGGER; Schema: public; Owner: db_cuti
--

CREATE TRIGGER trg_update_stock_on_approval AFTER INSERT OR UPDATE ON public.approval FOR EACH ROW EXECUTE PROCEDURE public.f_update_stock_barang();


--
-- TOC entry 2883 (class 2606 OID 24662)
-- Name: parameter_value fk_parameter; Type: FK CONSTRAINT; Schema: public; Owner: db_cuti
--

ALTER TABLE ONLY public.parameter_value
    ADD CONSTRAINT fk_parameter FOREIGN KEY (parameter_id) REFERENCES public.parameter(id);


-- Completed on 2023-09-20 20:34:26

--
-- PostgreSQL database dump complete
--

