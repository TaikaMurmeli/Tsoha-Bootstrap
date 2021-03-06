-- Lisää CREATE TABLE lauseet tähän tiedostoon

CREATE TABLE Ryhma(
  id SERIAL PRIMARY KEY,
  nimi varchar(50) NOT NULL,
  kuvaus varchar(400)
);

CREATE TABLE Kayttaja(
  id SERIAL PRIMARY KEY, -- SERIAL tyyppinen pääavain pitää huolen, että tauluun lisätyllä rivillä on aina uniikki pääavain. Kätevää!
  nimi varchar(50) NOT NULL, -- Muista erottaa sarakkeiden määrittelyt pilkulla!
  salasana varchar(50) NOT NULL,
  ryhma_id INTEGER REFERENCES Ryhma(id)
);

-- CREATE TABLE Kayttajaryhma(
--   kayttaja_id INTEGER REFERENCES Kayttaja(id),
--   ryhma_id INTEGER REFERENCES Ryhma(id)
-- );

CREATE TABLE Aihe(
  id SERIAL PRIMARY KEY,
  nimi varchar(50) NOT NULL,
  kuvaus varchar(400)
);

CREATE TABLE Kirjoitus(
  id SERIAL PRIMARY KEY,
  aihe_id INTEGER REFERENCES Aihe(id),
  nimi varchar(50) NOT NULL,
  sisalto varchar(4000) NOT NULL,
  julkaistu TIMESTAMP,
  julkaisija INTEGER REFERENCES Kayttaja(id)
);

CREATE TABLE Kommentti(
  id SERIAL PRIMARY KEY,
  kirjoitus_id INTEGER REFERENCES Kirjoitus(id),
  sisalto varchar(4000) NOT NULL,
  julkaistu TIMESTAMP,
  julkaisija INTEGER REFERENCES Kayttaja(id)
);

CREATE TABLE KirjoituksenLukenutKayttaja(
  kirjoitus_id INTEGER REFERENCES Kirjoitus(id),
  kayttaja_id INTEGER REFERENCES Kayttaja(id)
);