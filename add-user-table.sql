CREATE TABLE `user` (
  id int(11) NOT NULL,
  name varchar(128) NOT NULL,
  email varchar(255) NOT NULL,
  password_hash varchar(255) NOT NULL,
  api_key varchar(255) NOT NULL,
  api_key_hash varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


ALTER TABLE user
  ADD PRIMARY KEY (id),
  ADD UNIQUE KEY email (email),
  ADD UNIQUE KEY api_key_hash (api_key_hash);


ALTER TABLE user
  MODIFY id int(11) NOT NULL AUTO_INCREMENT;