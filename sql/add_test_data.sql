-- Lisää INSERT INTO lauseet tähän tiedostoon
INSERT INTO Kayttaja (id, nimi, salasana) VALUES ('1', 'Make-Viljami', 'lol');
INSERT INTO Ryhma (id, nimi, kuvaus) VALUES ('1', 'Admin', 'Ylläpitäjät');
INSERT INTO Ryhma (id, nimi, kuvaus) VALUES ('2', 'Käyttäjä', 'Foorumin keskustelijat');
INSERT INTO Aihe (id, nimi, kuvaus) VALUES ('1', 'Kebab', 'Eilen oli tanaan ei');
INSERT INTO Kirjoitus (id, aihe_id, nimi, sisalto, julkaistu, julkaisija) VALUES ('1', '1', 'Trolling is a art', 'Trolo', NOW()), '1';
INSERT INTO Kommentti (id, sisalto, julkaistu, julkaisija) VALUES ('1', 'lol', NOW(), '1');