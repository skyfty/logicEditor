/*
 Navicat Premium Data Transfer

 Source Server         : root
 Source Server Type    : MySQL
 Source Server Version : 50726
 Source Host           : localhost:3306
 Source Schema         : logiceditor

 Target Server Type    : MySQL
 Target Server Version : 50726
 File Encoding         : 65001

 Date: 26/03/2024 11:19:02
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for klondike
-- ----------------------------
DROP TABLE IF EXISTS `klondike`;
CREATE TABLE `klondike`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `levelGroupId` int(11) NULL DEFAULT NULL,
  `sequenceId` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 253 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of klondike
-- ----------------------------
INSERT INTO `klondike` VALUES (252, '关卡包1', 46, 1);

-- ----------------------------
-- Table structure for levelgroup
-- ----------------------------
DROP TABLE IF EXISTS `levelgroup`;
CREATE TABLE `levelgroup`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 47 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of levelgroup
-- ----------------------------
INSERT INTO `levelgroup` VALUES (46, '类型0 ');

-- ----------------------------
-- Table structure for matchingmodel
-- ----------------------------
DROP TABLE IF EXISTS `matchingmodel`;
CREATE TABLE `matchingmodel`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pokeParts_id` int(11) NULL DEFAULT NULL,
  `parts_id` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fk_matchingmodel_jk1`(`pokeParts_id`) USING BTREE,
  INDEX `fk_matchingmodel_jk2`(`parts_id`) USING BTREE,
  CONSTRAINT `fk_matchingmodel_jk1` FOREIGN KEY (`pokeParts_id`) REFERENCES `pokeparts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_matchingmodel_jk2` FOREIGN KEY (`parts_id`) REFERENCES `pokeparts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of matchingmodel
-- ----------------------------
INSERT INTO `matchingmodel` VALUES (12, 109, 103);
INSERT INTO `matchingmodel` VALUES (13, 110, 103);

-- ----------------------------
-- Table structure for modelgroup
-- ----------------------------
DROP TABLE IF EXISTS `modelgroup`;
CREATE TABLE `modelgroup`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `poke_id` int(11) NULL DEFAULT NULL,
  `picture` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `amount` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `picture`(`picture`) USING BTREE,
  INDEX `fk_poke_id`(`poke_id`) USING BTREE,
  CONSTRAINT `fk_poke_id` FOREIGN KEY (`poke_id`) REFERENCES `poke` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 64 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of modelgroup
-- ----------------------------
INSERT INTO `modelgroup` VALUES (57, 1245, 'resource/parts/lingjian_mutou_02.fbx', '20');
INSERT INTO `modelgroup` VALUES (58, 1245, 'resource/parts/lingjian_mutou_01.fbx', '74');
INSERT INTO `modelgroup` VALUES (60, 1245, 'resource/parts/lingjian_mutou_03.fbx', '3');
INSERT INTO `modelgroup` VALUES (61, 1245, 'resource/parts/AK-74.fbx', '1');
INSERT INTO `modelgroup` VALUES (62, 1246, 'resource/parts/lingjian_mutou_01.fbx', '1');
INSERT INTO `modelgroup` VALUES (63, 1245, 'resource/parts/07.fbx', '4');

-- ----------------------------
-- Table structure for parts
-- ----------------------------
DROP TABLE IF EXISTS `parts`;
CREATE TABLE `parts`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `filepath` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 53 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of parts
-- ----------------------------
INSERT INTO `parts` VALUES (11, 'jianzhu_mutoufangzi_01.fbx', 'resource/parts/jianzhu_mutoufangzi_01.fbx', 'jianzhu_mutoufangzi_01');
INSERT INTO `parts` VALUES (12, 'lingjian_mutou_01.fbx', 'resource/parts/lingjian_mutou_01.fbx', 'lingjian_mutou_01');
INSERT INTO `parts` VALUES (13, 'lingjian_mutou_04.fbx', 'resource/parts/lingjian_mutou_04.fbx', 'lingjian_mutou_04');
INSERT INTO `parts` VALUES (14, 'lingjian_mutou_03.fbx', 'resource/parts/lingjian_mutou_03.fbx', 'lingjian_mutou_03');
INSERT INTO `parts` VALUES (16, 'lingjian_mutou_02.fbx', 'resource/parts/lingjian_mutou_02.fbx', 'lingjian_mutou_02');
INSERT INTO `parts` VALUES (17, 'lingjian_mutou_05.fbx', 'resource/parts/lingjian_mutou_05.fbx', 'lingjian_mutou_05');
INSERT INTO `parts` VALUES (18, 'lingjian_shitou_01.fbx', 'resource/parts/lingjian_shitou_01.fbx', 'lingjian_shitou_01');
INSERT INTO `parts` VALUES (19, 'lingjian_shitou_07.fbx', 'resource/parts/lingjian_shitou_07.fbx', 'lingjian_shitou_07');
INSERT INTO `parts` VALUES (20, 'lingjian_shitou_06.fbx', 'resource/parts/lingjian_shitou_06.fbx', 'lingjian_shitou_06');
INSERT INTO `parts` VALUES (21, 'lingjian_shitou_05.fbx', 'resource/parts/lingjian_shitou_05.fbx', 'lingjian_shitou_05');
INSERT INTO `parts` VALUES (22, 'lingjian_shitou_03.fbx', 'resource/parts/lingjian_shitou_03.fbx', 'lingjian_shitou_03');
INSERT INTO `parts` VALUES (23, 'lingjian_shitou_04.fbx', 'resource/parts/lingjian_shitou_04.fbx', 'lingjian_shitou_04');
INSERT INTO `parts` VALUES (24, 'apple.fbx', 'resource/parts/apple.fbx', 'apple');
INSERT INTO `parts` VALUES (26, 'lingjian_fangzi01.fbx', 'resource/parts/lingjian_fangzi01.fbx', 'lingjian_fangzi01');
INSERT INTO `parts` VALUES (27, 'zhuangshi_boli_01.fbx', 'resource/parts/zhuangshi_boli_01.fbx', 'zhuangshi_boli_01');
INSERT INTO `parts` VALUES (31, '00.fbx', 'resource/parts/00.fbx', '00');
INSERT INTO `parts` VALUES (32, '01.fbx', 'resource/parts/01.fbx', '01');
INSERT INTO `parts` VALUES (33, '05.fbx', 'resource/parts/05.fbx', '05');
INSERT INTO `parts` VALUES (34, '06.fbx', 'resource/parts/06.fbx', '06');
INSERT INTO `parts` VALUES (35, '07.fbx', 'resource/parts/07.fbx', '07');
INSERT INTO `parts` VALUES (36, '08.fbx', 'resource/parts/08.fbx', '08');
INSERT INTO `parts` VALUES (37, '10.fbx', 'resource/parts/10.fbx', '10');
INSERT INTO `parts` VALUES (38, '09.fbx', 'resource/parts/09.fbx', '09');
INSERT INTO `parts` VALUES (39, '11.fbx', 'resource/parts/11.fbx', '11');
INSERT INTO `parts` VALUES (40, '12.fbx', 'resource/parts/12.fbx', '12');
INSERT INTO `parts` VALUES (41, '13.fbx', 'resource/parts/13.fbx', '13');
INSERT INTO `parts` VALUES (42, '14.fbx', 'resource/parts/14.fbx', '14');
INSERT INTO `parts` VALUES (43, '17.fbx', 'resource/parts/17.fbx', '17');
INSERT INTO `parts` VALUES (44, '15.fbx', 'resource/parts/15.fbx', '15');
INSERT INTO `parts` VALUES (45, '16.fbx', 'resource/parts/16.fbx', '16');
INSERT INTO `parts` VALUES (46, '18.fbx', 'resource/parts/18.fbx', '18');
INSERT INTO `parts` VALUES (47, '19.fbx', 'resource/parts/19.fbx', '19');
INSERT INTO `parts` VALUES (48, '20.fbx', 'resource/parts/20.fbx', '20');
INSERT INTO `parts` VALUES (49, '02.fbx', 'resource/parts/02.fbx', '02');
INSERT INTO `parts` VALUES (50, '04.fbx', 'resource/parts/04.fbx', '04');
INSERT INTO `parts` VALUES (51, '03.fbx', 'resource/parts/03.fbx', '03');
INSERT INTO `parts` VALUES (52, 'AK-74.fbx', 'resource/parts/AK-74.fbx', 'AK-74');

-- ----------------------------
-- Table structure for poke
-- ----------------------------
DROP TABLE IF EXISTS `poke`;
CREATE TABLE `poke`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `levelGroupId` int(11) NULL DEFAULT NULL,
  `KlondikeId` int(11) NULL DEFAULT NULL,
  `zoom` int(11) NULL DEFAULT NULL,
  `timeLimit` int(11) NULL DEFAULT NULL,
  `sequenceId` int(11) NULL DEFAULT NULL,
  `list` int(11) NULL DEFAULT NULL,
  `gold` int(11) NULL DEFAULT NULL,
  `experience` int(11) NULL DEFAULT NULL,
  `diamond` int(11) NULL DEFAULT NULL,
  `prop` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `stamina` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `PositionX` float NULL DEFAULT 0,
  `PositionY` float NULL DEFAULT 0,
  `PositionZ` float NULL DEFAULT 0,
  `RotationX` float NULL DEFAULT 0,
  `RotationY` float NULL DEFAULT 0,
  `RotationZ` float NULL DEFAULT 0,
  `ScaleX` float NULL DEFAULT 0,
  `ScaleY` float NULL DEFAULT 0,
  `ScaleZ` float NULL DEFAULT 0,
  `Projection` enum('Orthographic','PerspectiveCamera') CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT 'PerspectiveCamera',
  `Size` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `Near` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `Far` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `x` float NULL DEFAULT NULL,
  `y` float NULL DEFAULT NULL,
  `w` float NULL DEFAULT NULL,
  `h` float NULL DEFAULT NULL,
  `FieldOfView` float NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fk_Klondike_levels`(`KlondikeId`) USING BTREE,
  INDEX `fk_Klondike_levels1`(`zoom`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1247 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of poke
-- ----------------------------
INSERT INTO `poke` VALUES (1200, ' 关卡 ', 46, 239, 3, 8, 1, 1, 1, 0, 0, '0', '0', 0, 0, 0, 0, 0, 0, 1, 1, 1, 'PerspectiveCamera', '400', '0.1', '1000', 0, 0, 100, 100, 60);
INSERT INTO `poke` VALUES (1230, '关卡1', 47, 250, 0, 60, 1, 0, 0, 0, 0, '0', '0', 0, 0, 0, 0, 0, 0, 1, 1, 1, 'PerspectiveCamera', '400', '0.1', '1000', 0, 0, 100, 100, 60);
INSERT INTO `poke` VALUES (1232, '关卡4', 46, 245, 1, 60, 4, 3, 0, 0, 0, '0', '0', 0, 0, 0, 0, 0, 0, 1, 1, 1, 'PerspectiveCamera', '400', '0.1', '1000', 0, 0, 100, 100, 60);
INSERT INTO `poke` VALUES (1235, '关卡7', 46, 245, 0, 60, 7, 6, 0, 0, 0, '0', '0', 0, 0, 0, 0, 0, 0, 1, 1, 1, 'PerspectiveCamera', '400', '0.1', '1000', 0, 0, 100, 100, 60);
INSERT INTO `poke` VALUES (1236, '关卡8', 46, 245, 0, 60, 8, 7, 0, 0, 0, '0', '0', 0, 0, 0, 0, 0, 0, 1, 1, 1, 'PerspectiveCamera', '400', '0.1', '1000', 0, 0, 100, 100, 60);
INSERT INTO `poke` VALUES (1237, '关卡9', 46, 245, 0, 60, 9, 8, 0, 0, 0, '0', '0', 0, 0, 0, 0, 0, 0, 1, 1, 1, 'PerspectiveCamera', '400', '0.1', '1000', 0, 0, 100, 100, 60);
INSERT INTO `poke` VALUES (1238, '关卡10', 46, 245, 0, 60, 10, 9, 0, 0, 0, '0', '0', 0, 0, 0, 0, 0, 0, 1, 1, 1, 'PerspectiveCamera', '400', '0.1', '1000', 0, 0, 100, 100, 60);
INSERT INTO `poke` VALUES (1239, '关卡10', 46, 239, 1, 60, 10, 0, 0, 0, 0, '0', '0', 0, 0, 0, 0, 0, 0, 1, 1, 1, 'PerspectiveCamera', '400', '0.1', '1000', 0, 0, 100, 100, 60);
INSERT INTO `poke` VALUES (1241, '关卡8', 46, 240, 3, 8, 8, 7, 0, 0, 0, '0', '0', 0, 0, 0, 0, 0, 0, 1, 1, 1, 'PerspectiveCamera', '400', '0.1', '1000', 0, 0, 100, 100, 60);
INSERT INTO `poke` VALUES (1242, '关卡9', 46, 240, 3, 8, 9, 8, 0, 0, 0, '0', '0', 0, 0, 0, 0, 0, 0, 1, 1, 1, 'PerspectiveCamera', '400', '0.1', '1000', 0, 0, 100, 100, 60);
INSERT INTO `poke` VALUES (1244, '关卡10', 46, 251, 1, 60, 10, 9, 0, 0, 0, '0', '0', 0, 0, 0, 0, 0, 0, 1, 1, 1, 'PerspectiveCamera', '400', '0.1', '1000', 0, 0, 100, 100, 60);
INSERT INTO `poke` VALUES (1245, '关卡11', 46, 252, 2, 630, 11, 0, 112, 250, 20, '0', '0', 100, 100, 200, 108, 0, 0, 2, 2, 2, 'PerspectiveCamera', '400', '0.1', '2000', 0, 0, 100, 100, 66);
INSERT INTO `poke` VALUES (1246, '关卡12', 46, 252, 2, 600, 12, 11, 0, 0, 0, '0', '0', 50, 50, 100, 0, 0, 0, 1, 1, 1, 'Orthographic', '600', '0.1', '2000', 0, 0, 100, 100, 40);

-- ----------------------------
-- Table structure for pokeparts
-- ----------------------------
DROP TABLE IF EXISTS `pokeparts`;
CREATE TABLE `pokeparts`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `poke_id` int(11) NULL DEFAULT NULL,
  `x` float NULL DEFAULT NULL,
  `y` float NULL DEFAULT NULL,
  `z` float NULL DEFAULT NULL,
  `picture` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `chartlet` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `rotationX` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `rotationY` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `rotationZ` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `antle` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fk_pokeParts_jk1`(`poke_id`) USING BTREE,
  INDEX `fk_pokeParts_jk2`(`picture`) USING BTREE,
  CONSTRAINT `fk_pokeParts_jk1` FOREIGN KEY (`poke_id`) REFERENCES `poke` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 112 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of pokeparts
-- ----------------------------
INSERT INTO `pokeparts` VALUES (100, '关卡1', 1246, 0, 0, 0, 'resource/parts/lingjian_mutou_01.fbx', 'resource/parts/apple_01_cr.png', '0', '0', '0', '1', '可拆解');
INSERT INTO `pokeparts` VALUES (101, '关卡2', 1246, 0, 0, 0, 'resource/parts/lingjian_mutou_01.fbx', 'resource/parts/apple_01_cr.png', '0', '0', '0', '1', '可拆解');
INSERT INTO `pokeparts` VALUES (103, '关卡2', 1245, 23, 10, 0, 'resource/parts/lingjian_mutou_01.fbx', 'resource/parts/apple_01_cr.png', '-3', '0', '0', '1', '可拆解');
INSERT INTO `pokeparts` VALUES (109, '关卡2', 1245, 16, 20, 20, 'resource/parts/lingjian_mutou_01.fbx', 'resource/parts/apple_01_cr.png', '0', '000', '0', '1', '可拆解');
INSERT INTO `pokeparts` VALUES (110, '关卡3', 1245, 0, 0, 0, 'resource/parts/AK-74.fbx', 'resource/parts/apple_01_cr.png', '10', '0', '18', '1', '可拆解');
INSERT INTO `pokeparts` VALUES (111, '关卡4', 1245, 0, 0, 0, 'resource/parts/07.fbx', 'resource/parts/apple_01_cr.png', '9', '20', '20', '1', '可拆解');

-- ----------------------------
-- Table structure for sequence_map
-- ----------------------------
DROP TABLE IF EXISTS `sequence_map`;
CREATE TABLE `sequence_map`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `levelGroupId` int(11) NULL DEFAULT NULL,
  `sequenceId` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of sequence_map
-- ----------------------------

-- ----------------------------
-- Table structure for sequencegroup
-- ----------------------------
DROP TABLE IF EXISTS `sequencegroup`;
CREATE TABLE `sequencegroup`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `levelGroupId` int(11) NULL DEFAULT NULL,
  `sequenceId` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of sequencegroup
-- ----------------------------

-- ----------------------------
-- Triggers structure for table levelgroup
-- ----------------------------
DROP TRIGGER IF EXISTS `rr`;
delimiter ;;
CREATE TRIGGER `rr` AFTER DELETE ON `levelgroup` FOR EACH ROW BEGIN
    -- 声明变量
    DECLARE deckGameIdToUpdate INT;
    
    -- 声明游标
    DECLARE cur CURSOR FOR SELECT id FROM klondike WHERE levelGroupId = OLD.id;
    
    -- 设置游标异常处理程序
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET deckGameIdToUpdate = NULL;
    
    -- 打开游标
    OPEN cur;
    
    read_loop: LOOP
        -- 获取当前游标结果
        FETCH cur INTO deckGameIdToUpdate;
        
        -- 检查是否没有更多结果
        IF deckGameIdToUpdate IS NULL THEN
            LEAVE read_loop;
        END IF;
        
        -- 删除满足条件的Klondike表数据
        DELETE FROM klondike WHERE levelGroupId = OLD.id;
        -- 删除满足条件的sequence_map表数据
        DELETE FROM sequence_map WHERE levelGroupId = OLD.id;
        -- 删除满足条件的sequenceGroup表数据
        DELETE FROM sequenceGroup WHERE levelGroupId = OLD.id;
    END LOOP;
    
    -- 关闭游标
    CLOSE cur;
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table modelgroup
-- ----------------------------
DROP TRIGGER IF EXISTS `modelgroup_del`;
delimiter ;;
CREATE TRIGGER `modelgroup_del` AFTER DELETE ON `modelgroup` FOR EACH ROW BEGIN  
    DELETE FROM pokeparts WHERE poke_id = OLD.poke_id AND picture = OLD.picture;
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table pokeparts
-- ----------------------------
DROP TRIGGER IF EXISTS `pokeparts_add`;
delimiter ;;
CREATE TRIGGER `pokeparts_add` AFTER INSERT ON `pokeparts` FOR EACH ROW BEGIN
    -- 减少满足new_picture的amount字段的值（这里假设new_picture是一个已知的值）  
    UPDATE modelgroup SET amount = amount - 1 WHERE poke_id = NEW.poke_id AND picture = NEW.picture;  
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table pokeparts
-- ----------------------------
DROP TRIGGER IF EXISTS `pokeparts_edit`;
delimiter ;;
CREATE TRIGGER `pokeparts_edit` AFTER UPDATE ON `pokeparts` FOR EACH ROW BEGIN   
    -- 增加满足OLD.picture的amount字段的值  
    UPDATE modelgroup SET amount = amount + 1 WHERE poke_id = OLD.poke_id AND picture = OLD.picture;  
      
    -- 减少满足new_picture的amount字段的值（这里假设new_picture是一个已知的值）  
    UPDATE modelgroup SET amount = amount - 1 WHERE poke_id = NEW.poke_id AND picture = NEW.picture;  
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table pokeparts
-- ----------------------------
DROP TRIGGER IF EXISTS `pokeparts_del`;
delimiter ;;
CREATE TRIGGER `pokeparts_del` AFTER DELETE ON `pokeparts` FOR EACH ROW BEGIN
    -- 检查是否存在满足条件的数据  
    DECLARE exists_in_pokeparts INT;  
    SELECT COUNT(*) INTO exists_in_pokeparts  
    FROM modelgroup  
    WHERE poke_id = OLD.poke_id AND picture = OLD.picture;  
      
    -- 如果存在，则删除pokeparts中的数据  
    IF exists_in_pokeparts > 0 THEN  
			UPDATE modelgroup SET amount = amount + 1 WHERE poke_id = OLD.poke_id AND picture = OLD.picture;  
    END IF;  
END
;;
delimiter ;

SET FOREIGN_KEY_CHECKS = 1;
