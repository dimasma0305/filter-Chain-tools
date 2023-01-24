# filterChainFuzzerAndGenerator 

English | [简体中文](./README_zh.md)

A fuzz and generator based on php and python filter chain.

Possible use scenarios:

- No document RCE
- CTF The Web in
- CTF The Misc in
- ... (more possible?)

## About

You can learn the principle and more details in the doc below

- [[idekCTF 2022\] Paywall - filter chain construction and extension](https://dqgom7v7dl.feishu.cn/docx/RL8cdsipLoYAMvxl8bJcIERznWH)

Also, thanks for the ideas provided by the following projects

- https://github.com/loknop https://gist.github.com/loknop/b27422d355ea1fd0d90d6dbc1e278d4d
- https://github.com/wupco/PHP_INCLUDE_TO_SHELL_CHAR_DICT
- https://github.com/synacktiv/php_filter_chain_generator

The purpose of each file in the project directory is as follows:

- Fuzzer.php dictionary needed for Fuzz filter chain
  - iconv_list.php Fuzz character set file, you can customize the corresponding encoding set according to the scene
  - Init Fuzzer includes files, basically no need to change
- Generator.py Filter chain for generating arbitrary payloads
- List of dictionaries in aview.py output .res folder
- get_dic.py convert single-character files in the .res folder to a custom dictionary.py dictionary
- dictionary.py single character dictionary, can be customized, default use get_dic.py generation

## Usage

### Fuzz

Fuzz relies on Fuzzer.php

Define the character set you need for fuzz in iconv_list.php

![image](https://user-images.githubusercontent.com/41804496/214242583-de1f6381-5a2f-4c0d-8522-eef49e121363.png)

Select the corresponding character set according to the corresponding environment:

```bash
iconv -l
```



![image](https://user-images.githubusercontent.com/41804496/214242703-6708ec67-b5af-45c6-83c9-a476a1bc33bb.png)

Set the parameters in the Fuzzer.php:

![img](https://cdn.nlark.com/yuque/0/2023/png/21803058/1674490644217-7ef63718-9106-4796-8d61-e31357f47d80.png)

Start Fuzz with the following command:

```bash
php Fuzzer.php
```

### Generator

Filter chain generation relies on Generator.py implementation.

Two modes are currently available:

- Chain generation using the original hexcode encoded letters in the .res folder
- Using dictionary generation in dictionary.py

If you want to use the first mode, the dictionary corresponding to hexcode is included with the project download, just set the parameters at the beginning of the file:

![img](https://cdn.nlark.com/yuque/0/2023/png/21803058/1674491278243-f9fc0f34-db7f-495f-a138-9eea1d250c30.png)

Of course, you can also generate your own according to the project principle.

If you use the second mode, the project also prepared a Fuzz good word dictionary in dictionary.py:

![img](https://cdn.nlark.com/yuque/0/2023/png/21803058/1674491375475-11fb475f-cd15-4032-8045-b62abd1612db.png)

You can also Fuzz according to your own needs, the process is roughly as follows:

- Set the required character set
- Run Fuzzer.php
- Use get_dic.py to extract the running dictionary from .res

Of course, if you are familiar with the principle, you can also use the method you want to modify the dictionary file dictionary.py.

When everything is ready, use the following command directly:

```bash
python Generator.py
```

That's it.
