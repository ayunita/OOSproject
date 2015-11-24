INSERT INTO persons VALUES (11, 'John', 'Doe', '1248 Blane Street', 'johndoe@yahoo.com', '3145542033');
INSERT INTO persons VALUES (22, 'Donald', 'Stein', '2734 Veltri Drive', 'dstein@yahoo.com', '3335552233');
INSERT INTO persons VALUES (33, 'Bridget', 'McManus', '113 Hedge Street', 'mmbridget@yahoo.com', '6475133212');
INSERT INTO persons VALUES (44, 'Marilyn', 'Austin', '742 Dennison Street', 'maustin@yahoo.com', '5144321234');
INSERT INTO persons VALUES (55, 'Nathaniel', 'Torres', '122 Carriage Court', 'ntorres@yahoo.com', '6477122233');

INSERT INTO users VALUES ('john', 'john123', 'a', 11, TO_DATE('31/1/2011 12:12:12', 'DD/MM/YYYY hh24:mi:ss'));
INSERT INTO users VALUES ('donald', 'donald123', 's', 22, TO_DATE('2/11/2013 2:19:32', 'DD/MM/YYYY hh24:mi:ss'));
INSERT INTO users VALUES ('donald2', 'donald123', 's', 22, TO_DATE('24/12/2013 19:17:2', 'DD/MM/YYYY hh24:mi:ss'));
INSERT INTO users VALUES ('marilyn', 'marilyn123', 's', 44, TO_DATE('12/5/2013 20:12:51', 'DD/MM/YYYY hh24:mi:ss'));
INSERT INTO users VALUES ('nathaniel', 'nathaniel123', 's', 55, TO_DATE('31/1/2015 13:11:32', 'DD/MM/YYYY hh24:mi:ss'));

INSERT INTO sensors VALUES (1, 'A', 'a', 'audio sensor');
INSERT INTO sensors VALUES (2, 'A', 'i', 'image sensor');
INSERT INTO sensors VALUES (3, 'B', 's', 'scalar sensor');
INSERT INTO sensors VALUES (4, 'C', 'a', 'audio sensor');
INSERT INTO sensors VALUES (5, 'E', 'i', 'image sensor');
INSERT INTO sensors VALUES (6, 'C', 's', 'scalar sensor');

INSERT INTO subscriptions VALUES (1, 22);
INSERT INTO subscriptions VALUES (2, 22);
INSERT INTO subscriptions VALUES (4, 22);
INSERT INTO subscriptions VALUES (4, 44);
INSERT INTO subscriptions VALUES (5, 44);

COMMIT;
