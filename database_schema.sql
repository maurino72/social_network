CREATE database if not EXISTS social_network;

use social_network;

CREATE TABLE IF NOT EXISTS users (
  id int(255) AUTO_INCREMENT NOT NULL,
  role varchar(255),
  email varchar(255),
  firstName varchar(255),
  lastName varchar(255),
  password varchar(255),
  nickname varchar(50),
  biografy varchar(255),
  active varchar(255),
  image varchar(255),
  CONSTRAINT users_uniques_fields UNIQUE (email, nickname),
  CONSTRAINT pk_users PRIMARY KEY (id)
)engine=InnoDB;

CREATE TABLE IF NOT EXISTS publications (
  id int(255) AUTO_INCREMENT NOT NULL,
  user_id int(255),
  publication MEDIUMTEXT,
  document VARCHAR(100),
  image VARCHAR(255),
  status VARCHAR(30),
  created_at DATETIME,
  CONSTRAINT pk_publications PRIMARY KEY (id),
  CONSTRAINT fk_publications_users FOREIGN KEY (user_id) REFERENCES users(id)
)engine=InnoDB;

CREATE TABLE IF NOT EXISTS following (
  id int(255) AUTO_INCREMENT NOT NULL,
  user_follows int(255),
  user_followed int(255),
  CONSTRAINT pk_following PRIMARY KEY (id),
  CONSTRAINT fk_following_users FOREIGN KEY (user_follows) REFERENCES users(id),
  CONSTRAINT fk_followed FOREIGN KEY (user_followed) REFERENCES users(id)
)engine=InnoDB;


CREATE TABLE IF NOT EXISTS private_messages (
  id int(255) AUTO_INCREMENT NOT NULL,
  message LONGTEXT,
  emitter int(255),
  receiver int(255),
  file VARCHAR(255),
  image VARCHAR(255),
  readed varchar(3),
  created_at DATETIME,
  CONSTRAINT pk_private_messages PRIMARY KEY (id),
  CONSTRAINT fk_emitter_privates FOREIGN KEY (emitter) REFERENCES users(id),
  CONSTRAINT fk_receiver_privates FOREIGN KEY (receiver) REFERENCES users(id)
)engine=InnoDB;

CREATE TABLE IF NOT EXISTS likes (
  id int(255) AUTO_INCREMENT NOT NULL,
  user_id int(255),
  publication_id int(255),
  CONSTRAINT pk_likes PRIMARY KEY (id),
  CONSTRAINT fk_likes_users FOREIGN KEY (user_id) REFERENCES users(id),
  CONSTRAINT fk_likes_publication FOREIGN KEY (publication_id) REFERENCES publications(id)
)engine=InnoDB;

CREATE TABLE IF NOT EXISTS notifications (
  id int(255) AUTO_INCREMENT NOT NULL,
  user_id int(255),
  type VARCHAR(255),
  type_id int(255),
  readed VARCHAR(3),
  created_at DATETIME,
  extra VARCHAR(100),
  CONSTRAINT pk_notifications PRIMARY KEY (id),
  CONSTRAINT fk_notifications_users FOREIGN KEY (user_id) REFERENCES users(id)
)engine=InnoDB;