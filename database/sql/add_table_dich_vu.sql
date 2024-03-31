CREATE TABLE `dich_vu`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NULL,
  `ten_dich_vu` varchar(255) NULL,
  `gia_ban` int NULL,
  `note` varchar(255) NULL,
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL,
  `deleted_at` timestamp NULL,
  PRIMARY KEY (`id`)
);


ALTER TABLE `hoa_don` 
ADD COLUMN `is_tra_gop` int NULL DEFAULT 0 AFTER `file`,
ADD COLUMN `da_tra` int NULL AFTER `is_tra_gop`,
ADD COLUMN `tien_dang_ky` int NULL AFTER `da_tra`;


ALTER TABLE `san_pham` 
ADD COLUMN `bien_so` varchar(255) NULL AFTER `sk_sm`,
ADD COLUMN `ten_chu_cu` varchar(255) NULL AFTER `bien_so`;

CREATE TABLE `chi_tiet_dich_vu`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NULL,
  `ma_hoa_don` int NULL,
  `ma_dich_vu` int NULL,
  `gia_ban` int NULL,
  `so_luong` int NULL,
  `note` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL,
  `deleted_at` timestamp NULL,
  PRIMARY KEY (`id`)
);

ALTER TABLE `chi_tiet_hoa_don` 
ADD COLUMN `ma_khuyen_mai` int NULL AFTER `deleted_at`;

ALTER TABLE `chi_tiet_dich_vu` 
ADD COLUMN `ma_khuyen_mai` int NULL AFTER `deleted_at`;

ALTER TABLE `san_pham` 
ADD COLUMN `mau` varchar(255) NULL AFTER `ten_chu_cu`;

ALTER TABLE `hoa_don` 
ADD COLUMN `note_gbn` varchar(600) NULL AFTER `tien_dang_ky`,
ADD COLUMN `ngay_hen_dang_ky` varchar(255) NULL AFTER `note_gbn`,
ADD COLUMN `ngay_viet_gbn` varchar(255) NULL AFTER `ngay_hen_dang_ky`,
ADD COLUMN `dang_ky` varchar(255) NULL AFTER `ngay_viet_gbn`,
ADD COLUMN `dia_diem_gbn` varchar(255) NULL AFTER `dang_ky`;
ADD COLUMN `note_them` varchar(500) NULL AFTER `dia_diem_gbn`;
ADD COLUMN `bien_so` varchar(255) NULL AFTER `dang_ky`;