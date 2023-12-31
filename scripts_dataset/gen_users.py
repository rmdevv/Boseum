import uuid
import random
from faker import Faker

fake = Faker('it_IT')

# CREATE TABLE Users (
#     id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
#     username VARCHAR(30) NOT NULL,
#     password VARCHAR(255) NOT NULL,
#     name VARCHAR(30) NOT NULL,
#     surname VARCHAR(30) NOT NULL,
#     isAmm BOOLEAN NOT NULL,
#     image INT REFERENCES Images(id),
#     birth_date DATE NOT NULL,
#     birth_place VARCHAR(30) NOT NULL,
#     biography VARCHAR(1000),
#     experience VARCHAR(1000)
# );

def generate_user():
    username = fake.user_name()
    name = fake.first_name()
    surname = fake.last_name()
    is_amm = False
    birth_date = str(random.randint(1997, 2004)) + fake.date_of_birth().strftime('%Y-%m-%d')[4:] # per anno cambiare prime 4 cifre e mettere random da 1995 a 2003
    birth_place = fake.city()
    biography = fake.text(max_nb_chars=10) # sarebbe 300
    experience = fake.text(max_nb_chars=10) # sarebbe 300

    return (
        username,
        name,
        surname,
        is_amm,
        birth_date,
        birth_place,
        biography,
        experience
    )

def generate_users(num):
    insert_query = '''
INSERT INTO Users(username, name, surname, isAmm, birth_date, birth_place, biography, experience)
VALUES 
'''
    for _ in range(num):
        insert_query = insert_query + f"{str(tuple(generate_user()))},\n"

    insert_query = insert_query[:-2]+";"
    return insert_query

print(generate_users(20))