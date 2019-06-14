use icomefromthenet;

CREATE TABLE vo_voucher_group (voucher_group_id INT UNSIGNED AUTO_INCREMENT NOT NULL, voucher_group_name VARCHAR(100) NOT NULL, voucher_group_slug VARCHAR(100) NOT NULL, is_disabled TINYINT(1) DEFAULT '0' NOT NULL, sort_order INT UNSIGNED NOT NULL, date_created DATETIME NOT NULL, UNIQUE INDEX vo_voucher_group_uiq1 (voucher_group_slug), PRIMARY KEY(voucher_group_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;

CREATE TABLE vo_voucher_gen_rule (voucher_gen_rule_id INT UNSIGNED AUTO_INCREMENT NOT NULL, voucher_rule_name VARCHAR(25) NOT NULL, voucher_rule_slug VARCHAR(25) NOT NULL, voucher_padding_char VARCHAR(255) NOT NULL, voucher_prefix VARCHAR(50) NOT NULL, voucher_suffix VARCHAR(50) NOT NULL, voucher_length SMALLINT UNSIGNED NOT NULL, date_created DATETIME NOT NULL, voucher_sequence_no INT UNSIGNED NOT NULL, voucher_sequence_strategy VARCHAR(20) NOT NULL, voucher_validate_rules LONGTEXT NOT NULL COMMENT '(DC2Type:array)', PRIMARY KEY(voucher_gen_rule_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;

CREATE TABLE vo_voucher_type (voucher_type_id INT UNSIGNED AUTO_INCREMENT NOT NULL, voucher_group_id INT UNSIGNED NOT NULL, voucher_gen_rule_id INT UNSIGNED NOT NULL, voucher_enabled_from DATETIME NOT NULL, voucher_enabled_to DATETIME NOT NULL, voucher_name VARCHAR(100) NOT NULL, voucher_name_slug VARCHAR(100) NOT NULL, voucher_description VARCHAR(500) NOT NULL, INDEX IDX_F1A0C5DC60F381CE (voucher_group_id), INDEX IDX_F1A0C5DC175B96 (voucher_gen_rule_id), UNIQUE INDEX vo_voucher_type_uiq1 (voucher_name, voucher_enabled_from), PRIMARY KEY(voucher_type_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;

CREATE TABLE vo_voucher_instance (voucher_instance_id INT UNSIGNED AUTO_INCREMENT NOT NULL, voucher_type_id INT UNSIGNED NOT NULL, voucher_code VARCHAR(255) NOT NULL, date_created DATETIME NOT NULL, INDEX IDX_9217622C681A694 (voucher_type_id), UNIQUE INDEX vo_voucher_instance_uiq1 (voucher_code), PRIMARY KEY(voucher_instance_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;

ALTER TABLE vo_voucher_type ADD CONSTRAINT vo_voucher_type_fk1 FOREIGN KEY (voucher_group_id) REFERENCES vo_voucher_group (voucher_group_id);

ALTER TABLE vo_voucher_type ADD CONSTRAINT vo_voucher_type_fk2 FOREIGN KEY (voucher_gen_rule_id) REFERENCES vo_voucher_gen_rule (voucher_gen_rule_id);

ALTER TABLE vo_voucher_instance ADD CONSTRAINT vo_voucher_instance_fk1 FOREIGN KEY (voucher_type_id) REFERENCES vo_voucher_type (voucher_type_id);