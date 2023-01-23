import os
import json

folder_path = 'res'
file_list = os.listdir(folder_path)

for file_name in file_list:
    file_path = os.path.join(folder_path, file_name)
    with open(file_path, 'r') as file:
        file_content = file.read()
    print(json.dumps({file_name: file_content}))
