CREATE DATABASE Wiki;
USE Wiki;

CREATE TABLE Roles (
                       id INT AUTO_INCREMENT PRIMARY KEY,
                       role_name VARCHAR(255) NOT NULL
);

CREATE TABLE Users (
                       id INT AUTO_INCREMENT PRIMARY KEY,
                       username VARCHAR(255) NOT NULL,
                       email VARCHAR(255) NOT NULL,
                       password VARCHAR(255) NOT NULL,
                       role_id INT,
                       FOREIGN KEY (role_id) REFERENCES Roles(id) ON DELETE SET NULL ON UPDATE CASCADE
);

CREATE TABLE Categories (
                            id INT AUTO_INCREMENT PRIMARY KEY,
                            name VARCHAR(255) NOT NULL
);

CREATE TABLE Tags (
                      id INT AUTO_INCREMENT PRIMARY KEY,
                      name VARCHAR(255) NOT NULL
);

CREATE TABLE Wikis (
                       id INT AUTO_INCREMENT PRIMARY KEY,
                       title VARCHAR(255) NOT NULL,
                       content TEXT NOT NULL,
                       author_id INT,
                       category_id INT,
                       is_archived TINYINT(1) NOT NULL DEFAULT 0,
                       FOREIGN KEY (author_id) REFERENCES Users(id) ON DELETE SET NULL ON UPDATE CASCADE,
                       FOREIGN KEY (category_id) REFERENCES Categories(id) ON DELETE SET NULL ON UPDATE CASCADE
);

CREATE TABLE WikiTags (
                          wiki_id INT,
                          tag_id INT,
                          PRIMARY KEY (wiki_id, tag_id),
                          FOREIGN KEY (wiki_id) REFERENCES Wikis(id) ON DELETE CASCADE ON UPDATE CASCADE,
                          FOREIGN KEY (tag_id) REFERENCES Tags(id) ON DELETE CASCADE ON UPDATE CASCADE
);

INSERT INTO Roles (role_name) VALUES ('Administrator'), ('Editor'), ('Viewer');

INSERT INTO Users (username, email, password, role_id) VALUES
                                                           ('adminuser', 'admin@example.com', '$2y$10$Vh7h43n5qHMbEIBAUisQC.nQkBA7tJe71/Fp/KQ6W3G8ZsBXRzyde', 1),
                                                           ('editoruser', 'editor@example.com', '$2y$10$43NXeHq5OxxUFeBca0E00upIwiNlAQLSzZQIrr7ZiSJ97apwxFPX2', 2),
                                                           ('vieweruser', 'viewer@example.com', '$2y$10$ux3Dw565Vrl/o8KCIcS3SuovwFgiReevf11W0q0EoSEJ8C.oHwiTa', 3);

INSERT INTO Categories (name) VALUES
                                  ('Technology'),
                                  ('Science'),
                                  ('History');

INSERT INTO Tags (name) VALUES
                            ('Innovation'),
                            ('Discovery'),
                            ('Ancient');

INSERT INTO Wikis (title, content, author_id, category_id) VALUES
                                                               ('The History of Computing', 'Content about the history of computers...', 2, 3),
                                                               ('Quantum Mechanics', 'Content about quantum mechanics...', 2, 2),
                                                               ('The Roman Empire', 'Content about the Roman Empire...', 2, 3);

INSERT INTO WikiTags (wiki_id, tag_id) VALUES
                                           (1, 1),
                                           (2, 2),
                                           (3, 3);