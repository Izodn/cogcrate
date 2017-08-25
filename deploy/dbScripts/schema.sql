DROP SCHEMA IF EXISTS game CASCADE;

CREATE SCHEMA game AUthORIZATION cogcrate;

ALTER DATABASE cogcrate SET search_path = 'game';

CREATE TABLE game.item (
	id BIGSERIAL PRIMARY KEY,
	name VARCHAR(64) NOT NULL
);

-- Populate item data
COPY game.item (name) FROM '/application/deploy/dbScripts/data/item.csv';

-- Simulate item removal over time
DELETE FROM game.item
WHERE
	id % 146 = 0
	OR id % 832 = 0;

CREATE TABLE game.playerbackpack (
	id BIGSERIAL PRIMARY KEY,
	capacity REAL NOT NULL DEFAULT 5.00
);

CREATE TABLE game.backpackitem (
	id BIGSERIAL PRIMARY KEY,
	name VARCHAR(64) NOT NULL,
	weight REAL NOT NULL DEFAULT 0.00
);
INSERT INTO game.backpackitem (name, weight)
VALUES
('healing drink', 0.25),
('combat knife', 0.5),
('antidote shot', 0.25),
('combat shield', 1.0),
('Beretta M92 (gun)', 1.0),
('Kevlar Vest', 2.0);

CREATE TABLE game.backpackitemlink (
	id SERIAL PRIMARY KEY,
	backpack BIGINT NOT NULL REFERENCES game.playerbackpack(id),
	item BIGINT NOT NULL REFERENCES game.backpackitem(id)
);