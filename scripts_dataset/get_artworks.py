import os
import requests
from PIL import Image
from artworks import artworks

# Funzione per scaricare e salvare un'immagine
def download_image(url, filename):
    response = requests.get(url)
    with open(filename, 'wb') as file:
        file.write(response.content)

# Funzione per ottenere le informazioni sull'opera
def get_artwork_info(artwork_id):
    url = f'https://collectionapi.metmuseum.org/public/collection/v1/objects/{artwork_id}'
    response = requests.get(url)
    artwork_data = response.json()
    return artwork_data

# Funzione principale
def main(artwork_ids):
    for artwork_id in artwork_ids:
        # Ottieni informazioni sull'opera
        artwork_data = get_artwork_info(artwork_id)

        measurements = artwork_data['measurements']
        title = artwork_data['title']

        # Stampa informazioni sull'opera
        print(f'{title}, {measurements[0].get('elementMeasurements')}')

        # Scarica l'immagine principale
        primary_image = artwork_data.get('primaryImage')
        download_image(primary_image, os.path.join('../../artworksImages', 'original', f'{artwork_id}_1.jpg'))

        # Scarica immagini secondarie (se disponibili)
        additional_images = artwork_data.get('additionalImages', [])
        for i, image_url in enumerate(additional_images, start=2):
            download_image(image_url, os.path.join('../../artworksImages', 'original', f'{artwork_id}_{i}.jpg'))

if __name__ == "__main__":
    os.makedirs('../../artworksImages/original', exist_ok=True)
    
    # artworks_ids = [artwork.get('id_met') for artwork in artworks]
    artwork_ids = [485543]

    main(artwork_ids)
