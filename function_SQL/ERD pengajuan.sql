CREATE TABLE "karyawan" (
  "id_karyawan" varchar PRIMARY KEY,
  "nama_karyawan" varchar,
  "alamat" varchar,
  "email" varchar
);

CREATE TABLE "users" (
  "id" integer PRIMARY KEY,
  "email" varchar,
  "password" varchar,
  "id_role" varchar
);

CREATE TABLE "role" (
  "id_role" integer PRIMARY KEY,
  "nama_role" varchar
);

CREATE TABLE "pengajuan_pettycash" (
  "id_pengajuan" varchar PRIMARY KEY,
  "nama_pengajuan" varchar,
  "nominal" integer,
  "nominal_approval" integer,
  "id_karyawan" varchar,
  "deskripsi" varchar
);

CREATE TABLE "pertanggung_jawaban" (
  "id_pertanggung_jawab" varchar PRIMARY KEY,
  "id_pengajuan" varchar,
  "nominal" integer,
  "deskripsi" varchar
);

CREATE TABLE "approval" (
  "id_approval" varchar PRIMARY KEY,
  "id_pengajuan" varchar,
  "id_karyawan" varchar,
  "status" varchar,
  "deskripsi" varchar
);

CREATE TABLE "barang" (
  "id_barang" varchar PRIMARY KEY,
  "nama_barang" varchar,
  "deskripsi" varchar
);

CREATE TABLE "stok" (
  "id_stok" integer PRIMARY KEY,
  "id_barang" varchar,
  "stok" integer
);

CREATE TABLE "pengajuan_barang" (
  "id_pengajuan" varchar PRIMARY KEY,
  "nama_pengajuan" varchar,
  "id_barang" varchar,
  "kuantitas" integer,
  "kuantitas_approval" integer,
  "deskripsi" varchar
);
