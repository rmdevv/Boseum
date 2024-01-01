from PIL import Image
import os

def resize_images(input_folder, output_folder, max_size):
    os.makedirs(output_folder, exist_ok=True)

    for filename in os.listdir(input_folder):
        if filename.endswith(('.jpg', '.jpeg', '.png')):
            input_path = os.path.join(input_folder, filename)
            output_path = os.path.join(output_folder, filename)

            with Image.open(input_path) as img:
                img.thumbnail((max_size, max_size), Image.Resampling.LANCZOS)

                img.save(output_path, format='JPEG')

if __name__ == "__main__":
    input_folder = '../../uploads/artworks/original'
    output_folder = '../../uploads/artworks'
    max_size = 800

    resize_images(input_folder, output_folder, max_size)