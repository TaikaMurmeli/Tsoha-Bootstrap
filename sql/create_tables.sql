-- Lisää CREATE TABLE lauseet tähän tiedostoon
CREATE TABLE Kayttaja(
  id SERIAL PRIMARY KEY, -- SERIAL tyyppinen pääavain pitää huolen, että tauluun lisätyllä rivillä on aina uniikki pääavain. Kätevää!
  name varchar(50) NOT NULL, -- Muista erottaa sarakkeiden määrittelyt pilkulla!
  password varchar(50) NOT NULL
);

CREATE TABLE Ryhma(
  id SERIAL PRIMARY KEY,
  name varchar(50) NOT NULL,
  description varchar(400)
);

CREATE TABLE Kayttajaryhma(
  kayttaja_id INTEGER REFERENCES Kayttaja(id),
  ryhma_id INTEGER REFERENCES Ryhma(id)
);

CREATE TABLE Aihe(
  id SERIAL PRIMARY KEY,
  name varchar(50) NOT NULL,
  description varchar(400)
);

CREATE TABLE Kirjoitus(
  id SERIAL PRIMARY KEY,
  aihe_id INTEGER REFERENCES Aihe(id),
  name varchar(50) NOT NULL,
  content varchar(4000) NOT NULL,
  published DATE,
  publisher INTEGER REFERENCES Kayttaja(id)
);

CREATE TABLE Kommentti(
  id SERIAL PRIMARY KEY,
  kirjoitus_id INTEGER REFERENCES Kirjoitus(id),
  content varchar(4000) NOT NULL,
  published DATE,
  publisher INTEGER REFERENCES Kayttaja(id)
);