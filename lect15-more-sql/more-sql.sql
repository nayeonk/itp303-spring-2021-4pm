-- Add album 'Fight On' by artist 'Spirit of Troy'
SELECT * 
FROM albums;

INSERT INTO albums (title, artist_id)
VALUES ('Fight On', 277);

SELECT * 
FROM artists
WHERE name LIKE '%spirit%';

INSERT INTO artists (name)
VALUES ('Spirit of Troy');

-- Double check that 'Fight On' was added to albums table
SELECT * 
FROM albums
ORDER BY album_id DESC;

-- Update track 'All My Love' composed by E. Schrody and L. Dimant to be 
-- part of the 'Fight On' album and composed by Tommy Trojan
SELECT * FROM tracks;

UPDATE tracks
SET album_id = 348, composer = 'Tommy Trojan'
WHERE track_id = 3316;

SELECT * FROM tracks
WHERE name = 'All My Love';

-- DELETE the album 'Fight On'
DELETE FROM albums
WHERE album_id = 348;

-- This error occurs when you try to delete a record that is being referenced
-- by another table. E.g. album_id 348 is being referenced in the tracks 
-- table (All My Love is a track in the Fight On album)
-- Error Code: 1451. Cannot delete or update a parent row: a foreign key constraint fails (`nayeon_song_db`.`tracks`, CONSTRAINT `tracks_ibfk_3` FOREIGN KEY (`album_id`) REFERENCES `albums` (`album_id`))

-- Two ways to handle this issue
-- 1) Delete the 'All My Love' track
-- 2) Nullfiy the album_id 368 in 'All My Love' track

UPDATE tracks
SET album_id = null
WHERE track_id = 3316;
-- OR this works too - this takes care of ALL tracks that have album_id 348
-- WHERE album_id = 368;

-- Create a view that displays all albums and their artist names
-- Only show album id, album title, and artist name
CREATE OR REPLACE VIEW album_artists AS
SELECT album_id, title, name, artists.artist_id
FROM albums
JOIN artists
	ON albums.artist_id = artists.artist_id;
    
-- To see the view, use select statement and reference the view name
SELECT * 
FROM album_artists;

-- AGGREGATE FUNCTIONS
SELECT COUNT(*), COUNT(composer)
FROM tracks;

-- What is the shortest song length, average song length, longest song length?
SELECT MIN(milliseconds), MAX(milliseconds), AVG(milliseconds)
FROM tracks;

-- Shows character length of each track name
SELECT CHAR_LENGTH(name), name
FROM tracks;

SELECT * FROM tracks;

-- Find the shortest track for EACH album
SELECT album_id, MIN(milliseconds), MAX(milliseconds), AVG(milliseconds)
FROM tracks
GROUP BY album_id;

-- For each artist, show the number of their albums
SELECT artist_id, COUNT(*)
FROM albums
GROUP BY artist_id;

-- Same as above, but also show the artist name
SELECT albums.artist_id, artists.name, COUNT(*)
FROM albums
JOIN artists
	ON albums.artist_id = artists.artist_id
GROUP BY albums.artist_id;