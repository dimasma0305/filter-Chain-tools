import json
import os

def extract_files(directory):
    files = {}
    for filename in os.listdir(directory):
        if len(filename) == 1:
            with open(os.path.join(directory, filename), 'r') as f:
                files[filename] = f.read()
    return files

def save(data, file_name):
    with open(file_name, 'w') as f:
        f.write('dictionary = {\n')
        for filename, content in data.items():
            f.write(f"'{filename}': '{content}',")
            f.write('\n')
        f.write('}')

def print_data(data):
    for filename, content in data.items():
        print(f"{filename}: {content}")


directory = 'res'
files = extract_files(directory)
print_data(files)                   
save(files, 'dictionary.py')







