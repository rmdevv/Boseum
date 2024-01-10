import os
import requests
from PIL import Image
import html

import random
from faker import Faker
from datetime import datetime

from dataset import ARTISTS, ARTSHOWS
from users_generator import insert_users_query

fake = Faker('it_IT')

def download_image(url, filename):
    response = requests.get(url)
    with open(filename, 'wb') as file:
        file.write(response.content)


def get_artwork(artwork_id):
    url = f'https://collectionapi.metmuseum.org/public/collection/v1/objects/{artwork_id}'
    response = requests.get(url)
    artwork_data = response.json()
    return artwork_data


def artworks_generator(artists, download_images=False):
    return_list = []
    id_artwork_tot  = 0
    for id_artist, artist in enumerate(artists, start=2):
        for id_artwork_rel, artwork in enumerate(artist.get('user_artworks')):
            
            id_artwork = id_artwork_tot + id_artwork_rel + 1

            artwork_data = get_artwork(artwork.get('id_met'))

            if(artwork_data.get('measurements')):
                height = int(artwork_data['measurements'][0].get('elementMeasurements').get('Height', 0))
                width = int(artwork_data['measurements'][0].get('elementMeasurements').get('Width', 0))
                length = int(artwork_data['measurements'][0].get('elementMeasurements').get('Length', 0))
                diameter = int(artwork_data['measurements'][0].get('elementMeasurements').get('Diameter', 0))
                if diameter > 0 and width==0 and length==0:
                    width = diameter
                    length = diameter
            else:
                height = 'NULL'
                width = 'NULL'
                length = 'NULL'

            main_image_url = artwork_data.get('primaryImage')
            additional_images_url = artwork_data.get('additionalImages', [])
            
            title = html.escape(artwork.get('title'))
            description = html.escape(artwork.get('description'))
            labels = artwork.get('labels')
            start_date = fake.date_between_dates(date_start=datetime(2010,1,1), date_end=datetime(2023,1,1))
            end_date = fake.date_between_dates(date_start=start_date, date_end=datetime(2023,12,31))

            artwork_images_url = [main_image_url] + additional_images_url
            
            db_url_list = []
            for i, artwork_image_url in enumerate(artwork_images_url):
                db_url_list.append(f'../uploads/artworks/{id_artwork}_{i}.jpg')
                if download_images:
                    download_image(artwork_image_url, os.path.join('../../uploads/artworks/original', f'{id_artwork}_{i}.jpg'))

            return_list.append(
                {
                    'id': id_artwork,
                    'title': title,
                    'main_image': db_url_list[0],
                    'description': description,
                    'height': height,
                    'width': width,
                    'length': length,
                    'start_date': start_date,
                    'end_date': end_date,
                    'upload_time': 'CURRENT_TIMESTAMP()',
                    'id_artist': id_artist,
                    'additional_images': db_url_list[1:],
                    'labels': labels
                }
            )

        id_artwork_tot = id_artwork_tot + len(artist.get('user_artworks'))

    return return_list


def insert_artworks_query(insert_list):
    insert_query = '''
INSERT INTO Artworks(id, title, main_image, description, height, width, length, start_date, end_date, upload_time, id_artist)
VALUES 
'''

    for a in insert_list:
        insert_query = insert_query + f"({a.get('id')}, '{a.get('title')}', '{a.get('main_image')}', '{a.get('description')}', {a.get('height')}, {a.get('width')}, {a.get('length')}, '{a.get('start_date')}', '{a.get('end_date')}', '{a.get('upload_time')}', {a.get('id_artist')}),\n"

    insert_query = insert_query[:-2]+";"
    return insert_query if '(' in insert_query else ''


def insert_artworks_labels_query(insert_list):
    insert_query = '''
INSERT INTO ArtworkLabels
VALUES 
'''

    for a in insert_list:
        for label in a.get('labels', []):
            if(label != ''):
                insert_query = insert_query + f"({a.get('id')}, '{label}'),\n"

    insert_query = insert_query[:-2]+";"
    return insert_query if '(' in insert_query else ''



def insert_artworks_details_query(insert_list):
    insert_query = '''
INSERT INTO ArtworkDetails
VALUES 
'''

    for a in insert_list:
        for additional_image in a.get('additional_images', []):
            insert_query = insert_query + f"({a.get('id')}, '{additional_image}'),\n"

    insert_query = insert_query[:-2]+";"
    return insert_query if '(' in insert_query else ''


def insert_artshow_query(artshows):
    insert_query = '''
INSERT INTO Artshows(title, description, image, start_date, end_date)
VALUES 
'''

    for id_a, a in enumerate(artshows, start=1):
        insert_query = insert_query + f"('{a.get('title')}', '{a.get('description')}', '../uploads/artshows/{id_a}.jpg', '{a.get('start_date')}', '{a.get('end_date')}'),\n"

    insert_query = insert_query[:-2]+";"
    return insert_query if '(' in insert_query else ''




if __name__ == "__main__":
    os.makedirs('../../uploads/artworks/original', exist_ok=True)
    
    return_list = artworks_generator(ARTISTS, download_images=False)

    print(insert_users_query() + '\n')
    print(insert_artworks_query(return_list) + '\n')
    print(insert_artworks_labels_query(return_list) + '\n')
    print(insert_artworks_details_query(return_list))
    print(insert_artshow_query(ARTSHOWS))

    