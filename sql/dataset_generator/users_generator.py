import random
from faker import Faker
import bcrypt
import html

from dataset import ARTISTS, ARTSHOWS

fake = Faker('it_IT')

def generate_user(id_user, user):
    name = fake.first_name_male() if user.get('gender', 'M').upper() == 'M' else fake.first_name_female()
    lastname = fake.last_name()
    username = name.lower()[:random.randint(len(name)//2, len(name))] + random.choice(['_', '-', '.']) + lastname.lower()[:random.randint(len(name)//2, len(name))] + random.choice(['', str(random.randint(0, 100))])

    image = f'../uploads/users/{id_user}.jpg'

    is_amm = False
    birth_date = str(random.randint(1997, 2004)) + fake.date_of_birth().strftime('%Y-%m-%d')[4:]
    birth_place = random.choice([fake.city(), 'Padova'])
    password = user.get('password', 'user')
    hashed_password = bcrypt.hashpw(password.encode('utf-8'), bcrypt.gensalt()).decode('utf-8')
    # print(bcrypt.checkpw(''.encode('utf-8'), ''.encode('utf-8')))
    biography = html.escape(user.get('biography', ''))
    experience = html.escape(user.get('experience', ''))

    return (
        username,
        hashed_password,
        name,
        lastname,
        is_amm,
        image,
        birth_date,
        birth_place,
        biography,
        experience
    )

def insert_users_query():
    insert_query = '''
INSERT INTO Users(username, password, name, lastname, isAmm, image, birth_date, birth_place, biography, experience)
VALUES 
'''
    for id_artist, artist in enumerate(ARTISTS, start=2):
        insert_query = insert_query + f"{str(tuple(generate_user(id_artist, artist)))},\n"

    insert_query = insert_query[:-2]+";"
    return insert_query
