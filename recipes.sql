-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Hostiteľ: localhost:8889
-- Čas generovania: Út 14.Máj 2019, 11:52
-- Verzia serveru: 5.7.25
-- Verzia PHP: 7.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Databáza: `recipes`
--

DELIMITER $$
--
-- Procedúry
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `ingredient_change_listener` (`input_id` INT)  BEGIN
 
 DECLARE v_finished INTEGER DEFAULT 0;
        DECLARE recipe_id_for_change INT(11) DEFAULT 0;
 
 DEClARE recipe_cursor CURSOR FOR 
 SELECT recipe_id FROM recipe_ingredient_items WHERE ingredient_id = input_id;
 
 DECLARE CONTINUE HANDLER 
        FOR NOT FOUND SET v_finished = 1;
 
 OPEN recipe_cursor;
 
 get_recipe_id: LOOP
 
 FETCH recipe_cursor INTO recipe_id_for_change;
 
 IF v_finished = 1 THEN 
 LEAVE get_recipe_id;
 END IF;

    SET @price := (SELECT TRUNCATE(SUM(recipe_ingredient_items.quantity*(ingredients.price*units.convt)), 2) AS price FROM (SELECT recipe_id_for_change AS subrecipe_id UNION SELECT subrecipe_id
FROM    (SELECT * from recipe_subrecipe_items
         ORDER BY recipe_id, subrecipe_id) recipes_sorted ,
        (SELECT @pv := recipe_id_for_change) initialisation
WHERE   find_in_set(recipes_sorted.recipe_id, @pv)
AND     length(@pv := concat(@pv, ',', subrecipe_id))) calculate_table
 LEFT JOIN recipe_ingredient_items ON recipe_ingredient_items.recipe_id = calculate_table.subrecipe_id
         LEFT JOIN ingredients ON ingredients.ingredient_id = recipe_ingredient_items.ingredient_id
         LEFT JOIN units ON units.unit_id = recipe_ingredient_items.unit_id);

    UPDATE recipes
    SET price = (SELECT @price)
    WHERE recipe_id = recipe_id_for_change;

 END LOOP get_recipe_id;
 
 CLOSE recipe_cursor;
 
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `recipe_change_listener` (IN `input_id` INT)  BEGIN
 
 DECLARE v_finished INTEGER DEFAULT 0;
        DECLARE recipe_id_for_change INT(11) DEFAULT 0;
 
 DEClARE recipe_cursor CURSOR FOR 
 select  recipe_id 
from    (select * from recipe_subrecipe_items
         order by subrecipe_id desc, recipe_id desc) recipes_sorted,
        (select @pv := input_id) initialisation
where   find_in_set(subrecipe_id, @pv)
and     length(@pv := concat(@pv, ',', recipe_id));
 
 DECLARE CONTINUE HANDLER 
        FOR NOT FOUND SET v_finished = 1;
 
 OPEN recipe_cursor;
 
 get_recipe_id: LOOP
 
 FETCH recipe_cursor INTO recipe_id_for_change;
 
 IF v_finished = 1 THEN 
 LEAVE get_recipe_id;
 END IF;

    SET @price := (SELECT TRUNCATE(SUM(recipe_ingredient_items.quantity*(ingredients.price*units.convt)), 2) AS price FROM (SELECT recipe_id_for_change AS subrecipe_id UNION SELECT subrecipe_id
FROM    (SELECT * from recipe_subrecipe_items
         ORDER BY recipe_id, subrecipe_id) recipes_sorted ,
        (SELECT @pv := recipe_id_for_change) initialisation
WHERE   find_in_set(recipes_sorted.recipe_id, @pv)
AND     length(@pv := concat(@pv, ',', subrecipe_id))) calculate_table
 LEFT JOIN recipe_ingredient_items ON recipe_ingredient_items.recipe_id = calculate_table.subrecipe_id
         LEFT JOIN ingredients ON ingredients.ingredient_id = recipe_ingredient_items.ingredient_id
         LEFT JOIN units ON units.unit_id = recipe_ingredient_items.unit_id);

    UPDATE recipes
    SET price = (SELECT @price)
    WHERE recipe_id = recipe_id_for_change;

 END LOOP get_recipe_id;
 
 CLOSE recipe_cursor;
 
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `ingredients`
--

CREATE TABLE `ingredients` (
  `ingredient_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `quantity` decimal(12,2) NOT NULL,
  `unit_id` int(11) UNSIGNED NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Sťahujem dáta pre tabuľku `ingredients`
--

INSERT INTO `ingredients` (`ingredient_id`, `name`, `quantity`, `unit_id`, `price`, `created`, `updated`) VALUES
(1, 'špagety cestoviny', '1.00', 4, '2.20', '2019-05-13 18:56:19', '2019-05-14 08:30:39'),
(2, 'baklažán', '1.00', 3, '1.00', '2019-05-13 18:56:19', '2019-05-14 11:25:20'),
(3, 'cesnak', '1.00', 3, '0.50', '2019-05-13 18:56:19', '2019-05-13 18:56:19'),
(4, 'olivový olej', '1.00', 2, '4.50', '2019-05-13 18:56:19', '2019-05-13 18:56:19'),
(5, 'paradajky', '4.00', 3, '2.50', '2019-05-13 18:56:19', '2019-05-13 18:56:19'),
(6, 'cukor', '5.00', 1, '0.80', '2019-05-13 18:56:19', '2019-05-13 18:56:19'),
(7, 'cibuľa', '1.00', 3, '1.50', '2019-05-13 18:56:19', '2019-05-13 18:56:19'),
(8, 'mlete maso', '100.00', 1, '6.50', '2019-05-13 18:56:19', '2019-05-13 18:56:19');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `recipes`
--

CREATE TABLE `recipes` (
  `recipe_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `serving` int(11) NOT NULL DEFAULT '1',
  `type` enum('recipe','subrecipe') NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `unit_id` int(11) UNSIGNED NOT NULL DEFAULT '10',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Sťahujem dáta pre tabuľku `recipes`
--

INSERT INTO `recipes` (`recipe_id`, `name`, `serving`, `type`, `price`, `unit_id`, `created`, `updated`) VALUES
(1, 'Špagety', 2, 'recipe', '22.00', 10, '2019-05-13 18:58:47', '2019-05-14 11:25:20'),
(2, 'Paradajkovo baklažánova omáčka', 2, 'subrecipe', '14.80', 1, '2019-05-13 18:58:47', '2019-05-14 11:24:47'),
(3, 'este nieco', 2, 'subrecipe', '3.50', 1, '2019-05-14 08:12:22', '2019-05-14 11:24:47'),
(4, 'este dalsi', 2, 'subrecipe', '1.00', 1, '2019-05-14 10:33:58', '2019-05-14 11:25:20');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `recipe_ingredient_items`
--

CREATE TABLE `recipe_ingredient_items` (
  `recipe_ingredient_item_id` int(11) UNSIGNED NOT NULL,
  `ingredient_id` int(11) UNSIGNED NOT NULL,
  `recipe_id` int(11) UNSIGNED NOT NULL,
  `quantity` decimal(12,2) NOT NULL,
  `unit_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Sťahujem dáta pre tabuľku `recipe_ingredient_items`
--

INSERT INTO `recipe_ingredient_items` (`recipe_ingredient_item_id`, `ingredient_id`, `recipe_id`, `quantity`, `unit_id`) VALUES
(11, 1, 1, '1.00', 4),
(12, 2, 1, '1.00', 3),
(13, 3, 1, '1.00', 3),
(14, 4, 1, '1.00', 2),
(57, 5, 2, '1.00', 3),
(58, 6, 2, '1.00', 1),
(59, 7, 2, '1.00', 3),
(60, 8, 2, '1.00', 1),
(71, 7, 3, '1.00', 3),
(75, 2, 4, '1.00', 3);

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `recipe_subrecipe_items`
--

CREATE TABLE `recipe_subrecipe_items` (
  `recipe_id` int(11) UNSIGNED NOT NULL,
  `subrecipe_id` int(11) UNSIGNED NOT NULL,
  `quantity` decimal(12,2) NOT NULL,
  `unit_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Sťahujem dáta pre tabuľku `recipe_subrecipe_items`
--

INSERT INTO `recipe_subrecipe_items` (`recipe_id`, `subrecipe_id`, `quantity`, `unit_id`) VALUES
(1, 2, '1.00', 1),
(2, 3, '1.00', 1),
(3, 4, '1.00', 1);

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `units`
--

CREATE TABLE `units` (
  `unit_id` int(11) UNSIGNED NOT NULL,
  `label` varchar(255) NOT NULL,
  `base_unit` int(11) UNSIGNED DEFAULT NULL,
  `convt` decimal(12,3) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Sťahujem dáta pre tabuľku `units`
--

INSERT INTO `units` (`unit_id`, `label`, `base_unit`, `convt`, `created`, `updated`) VALUES
(1, 'gram', NULL, '1.000', '2019-05-13 16:34:32', '2019-05-13 16:34:32'),
(2, 'liter', NULL, '1.000', '2019-05-13 16:34:32', '2019-05-13 16:34:32'),
(3, 'kus', NULL, '1.000', '2019-05-13 16:34:32', '2019-05-13 16:49:00'),
(4, 'balíček', NULL, '1.000', '2019-05-13 16:34:32', '2019-05-13 16:49:00'),
(5, 'kilogram', 1, '0.001', '2019-05-13 16:41:57', '2019-05-13 16:42:45'),
(6, 'decagram', 1, '0.100', '2019-05-13 16:41:57', '2019-05-13 16:41:57'),
(7, 'miligram', 1, '1000.000', '2019-05-13 16:41:57', '2019-05-13 16:41:57'),
(8, 'hectoliter', 2, '0.010', '2019-05-13 16:45:25', '2019-05-13 16:45:25'),
(9, 'centiliter', 2, '100.000', '2019-05-13 16:45:25', '2019-05-13 16:45:25'),
(10, 'mililiter', 2, '1000.000', '2019-05-13 16:45:25', '2019-05-13 16:45:25');

--
-- Kľúče pre exportované tabuľky
--

--
-- Indexy pre tabuľku `ingredients`
--
ALTER TABLE `ingredients`
  ADD PRIMARY KEY (`ingredient_id`);

--
-- Indexy pre tabuľku `recipes`
--
ALTER TABLE `recipes`
  ADD PRIMARY KEY (`recipe_id`);

--
-- Indexy pre tabuľku `recipe_ingredient_items`
--
ALTER TABLE `recipe_ingredient_items`
  ADD PRIMARY KEY (`recipe_ingredient_item_id`),
  ADD KEY `ingredient_id` (`ingredient_id`),
  ADD KEY `ingredient_recipe_id` (`recipe_id`);

--
-- Indexy pre tabuľku `recipe_subrecipe_items`
--
ALTER TABLE `recipe_subrecipe_items`
  ADD PRIMARY KEY (`recipe_id`,`subrecipe_id`),
  ADD KEY `subrecipe_id` (`subrecipe_id`) USING BTREE;

--
-- Indexy pre tabuľku `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`unit_id`);

--
-- AUTO_INCREMENT pre exportované tabuľky
--

--
-- AUTO_INCREMENT pre tabuľku `ingredients`
--
ALTER TABLE `ingredients`
  MODIFY `ingredient_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pre tabuľku `recipes`
--
ALTER TABLE `recipes`
  MODIFY `recipe_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pre tabuľku `recipe_ingredient_items`
--
ALTER TABLE `recipe_ingredient_items`
  MODIFY `recipe_ingredient_item_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT pre tabuľku `units`
--
ALTER TABLE `units`
  MODIFY `unit_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Obmedzenie pre exportované tabuľky
--

--
-- Obmedzenie pre tabuľku `recipe_ingredient_items`
--
ALTER TABLE `recipe_ingredient_items`
  ADD CONSTRAINT `ingredient_id` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients` (`ingredient_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ingredient_recipe_id` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`recipe_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Obmedzenie pre tabuľku `recipe_subrecipe_items`
--
ALTER TABLE `recipe_subrecipe_items`
  ADD CONSTRAINT `recipe_id` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`recipe_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `subrecipe_id` FOREIGN KEY (`subrecipe_id`) REFERENCES `recipes` (`recipe_id`) ON DELETE CASCADE ON UPDATE CASCADE;
