/* Create database */
CREATE DATABASE photosite;

USE photosite;


CREATE TABLE categories (
  id int NOT NULL AUTO_INCREMENT,
  name tinytext,
  description text,
  PRIMARY KEY (id)
);

CREATE TABLE digitalCameras (
  id int NOT NULL AUTO_INCREMENT,
  serial tinytext,
  manufacturer tinytext,
  model tinytext,
  imageWidth smallint(6),
  imageHeight smallint(6),
  PRIMARY KEY (id)
);

CREATE TABLE digitalPhotos (
  id int NOT NULL AUTO_INCREMENT,
  cameraID int,
  lensID int,
  shutterSpeedNumerator decimal(8,2),
  shutterSpeedDenominator decimal(8,2),
  aperture decimal(6,2),
  iso int,
  focalLength smallint(6),
  flash tinyint(1),
  time time,
  date date,
  locationID int,
  mode tinytext,
  categoryID int,
  oldFilename tinytext,
  extraInfo text,
  PRIMARY KEY (id)
);

CREATE TABLE filmCameras (
  id int NOT NULL AUTO_INCREMENT,
  serial tinytext,
  manufacturer tinytext,
  model tinytext,
  PRIMARY KEY (id)
);

CREATE TABLE filmPhotos (
  id int NOT NULL AUTO_INCREMENT,
  filmCameraID int,
  lensID int,
  filmstockID int,
  date date,
  locationID int,
  categoryID int,
  oldFilename tinytext,
  extraInfo text,
  PRIMARY KEY (id)
);

CREATE TABLE filmstocks (
  id int NOT NULL AUTO_INCREMENT,
  manufacturer tinytext,
  name tinytext,
  cropFactor decimal(4,2),
  format tinytext,
  iso smallint(6),
  colour tinyint(1),
  PRIMARY KEY (id)
);

CREATE TABLE lenses (
  id int NOT NULL AUTO_INCREMENT,
  serial tinytext,
  manufacturer tinytext,
  name tinytext,
  prime tinyint(1),
  minFocalLength smallint(6),
  maxFocalLength smallint(6),
  minAperture decimal(6,2),
  maxAperture decimal(6,2),
  mount tinytext,
  blades smallint(6),
  autofocus tinyint(1),
  PRIMARY KEY (id)
);

CREATE TABLE locations (
  id int NOT NULL AUTO_INCREMENT,
  firstLine tinytext,
  secondLine tinytext,
  city tinytext,
  county tinytext,
  state tinytext,
  country tinytext,
  countryGroup tinytext,
  continent tinytext,
  latitude decimal(8,5),
  longitude decimal(8,5),
  timezone tinytext,
  PRIMARY KEY (id)
);

CREATE TABLE users (
  id int NOT NULL AUTO_INCREMENT,
  username tinytext,
  passwordhash text,
  salt text,
  addphotoperm tinyint(1),
  addotherperm tinyint(1),
  deleteperm tinyint(1),
  PRIMARY KEY (id)
);

ALTER TABLE filmPhotos
ADD CONSTRAINT FK_filmPhotos_filmCameraID
FOREIGN KEY (filmCameraID) REFERENCES filmCameras(id),

ADD CONSTRAINT FK_filmPhotos_lensID
FOREIGN KEY (lensID) REFERENCES lenses(id),

ADD CONSTRAINT FK_filmPhotos_filmstockID
FOREIGN KEY (filmstockID) REFERENCES filmstocks(id),

ADD CONSTRAINT FK_filmPhotos_locationID
FOREIGN KEY (locationID) REFERENCES locations(id),

ADD CONSTRAINT FK_filmPhotos_categoryID
FOREIGN KEY (categoryID) REFERENCES categories(id);


ALTER TABLE digitalPhotos
ADD CONSTRAINT FK_digitalPhotos_digitalCameraID
FOREIGN KEY (cameraID) REFERENCES digitalCameras(id),

ADD CONSTRAINT FK_digitalPhotos_lensID
FOREIGN KEY (lensID) REFERENCES lenses(id),

ADD CONSTRAINT FK_digitalPhotos_locationID
FOREIGN KEY (locationID) REFERENCES locations(id),

ADD CONSTRAINT FK_digitalPhotos_categoryID
FOREIGN KEY (categoryID) REFERENCES categories(id);
/*
CREATE TABLE photos (
  photoID int NOT NULL,
  digitalPhotoID int,
  filmPhotoID int,
  digital tinyint(1),
  landscape tinyint(1),
  PRIMARY KEY (photoID)
);*/