DROP SCHEMA IF EXISTS game CASCADE;

CREATE SCHEMA game AUthORIZATION cogcrate;

ALTER DATABASE cogcrate SET search_path = 'game';

CREATE TABLE item (
	id BIGSERIAL PRIMARY KEY,
	name VARCHAR(64) NOT NULL
);

-- Populate item data
COPY item (name) FROM '/application/deploy/dbScripts/data/item.csv';

-- Simulate item removal over time
DELETE FROM item
WHERE
	id % 146 = 0
	OR id % 832 = 0;

CREATE TABLE playerbackpack (
	id BIGSERIAL PRIMARY KEY,
	capacity REAL NOT NULL DEFAULT 5.00
);

CREATE TABLE backpackitem (
	id BIGSERIAL PRIMARY KEY,
	name VARCHAR(64) NOT NULL,
	weight REAL NOT NULL DEFAULT 0.00
);
INSERT INTO backpackitem (name, weight)
VALUES
('healing drink', 0.25),
('combat knife', 0.5),
('antidote shot', 0.25),
('combat shield', 1.0),
('Beretta M92 (gun)', 1.0),
('Kevlar Vest', 2.0);

CREATE TABLE backpackitemlink (
	backpack BIGINT NOT NULL REFERENCES playerbackpack(id),
	item BIGINT NOT NULL REFERENCES backpackitem(id)
);