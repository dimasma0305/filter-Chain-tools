# filterChainFuzzerAndGenerator 

[English](./README.md) | 简体中文

一个基于php和python的Filter链的fuzz和生成程序。

可能使用的场景:

- 无文件RCE 
- CTF中的Web
- CTF中的MISC
- ......（更多可能？）

## About

你可以在下面这篇文档中了解原理和更多细节

- [【idekCTF 2022】Paywall — filter链构造和扩展](https://dqgom7v7dl.feishu.cn/docx/RL8cdsipLoYAMvxl8bJcIERznWH)

此外，感谢下面的项目提供的思路

- https://github.com/loknop https://gist.github.com/loknop/b27422d355ea1fd0d90d6dbc1e278d4d
- https://github.com/wupco/PHP_INCLUDE_TO_SHELL_CHAR_DICT
- https://github.com/synacktiv/php_filter_chain_generator

项目目录各个文件的作用如下：

- Fuzzer.php 用于Fuzz filter链需要的字典
  - iconv_list.php Fuzz中字符集文件，可以按照场景自定义对应编码集
  - init Fuzzer包含用文件，基本无需改动
- Generator.py 用于生成任意payload的Filter链
- aview.py 输出.res 文件夹中字典一览
- get_dic.py 将.res文件夹中的单字符文件转换为自定义的dictionary.py字典
- dictionary.py 单字符字典，可以自定义，默认使用get_dic.py生成

## Usage

### Fuzz

Fuzz依靠Fuzzer.php实现

在iconv_list.php中定义你fuzz需要的字符集

![img](https://cdn.nlark.com/yuque/0/2023/png/21803058/1674490580048-be078983-c35f-4b53-9597-871ac1a4a2d0.png)

根据对应环境选择对应的字符集合：

```Bash
iconv -l
```

![img](https://cdn.nlark.com/yuque/0/2023/png/21803058/1674490476175-f28badfa-59de-4265-8bae-43e0bd65da3e.png)

在Fuzzer.php中设置好参数：

![img](https://cdn.nlark.com/yuque/0/2023/png/21803058/1674490644217-7ef63718-9106-4796-8d61-e31357f47d80.png)

使用下面命令即可开始Fuzz：

```Bash
php Fuzzer.php
```

### Generator

Filter链的生成依靠Generator.py实现。

目前提供两种模式：

- 使用.res文件夹中原有的hexcode编码字母的链子生成
- 使用dictionary.py中的字典生成

如果你要使用第一种模式，项目下载时就附带好了对应hexcode的字典，只需要在文件开头设置参数即可：

![img](https://cdn.nlark.com/yuque/0/2023/png/21803058/1674491278243-f9fc0f34-db7f-495f-a138-9eea1d250c30.png)

当然您也可以根据项目原理自己生成。

如果您使用第二种模式，项目也准备了一份Fuzz好的单字母字典在dictionary.py中：

![img](https://cdn.nlark.com/yuque/0/2023/png/21803058/1674491375475-11fb475f-cd15-4032-8045-b62abd1612db.png)

您也可以根据自己的需求Fuzz，流程大致如下：

- 设定好需要的字符集
- 运行Fuzzer.php
- 使用get_dic.py程序从.res中提取跑好的字典

当然您如果熟悉原理，也可以用您想要的方法，自行修改字典文件dictionary.py。

当一切准备就绪，直接使用下面命令：

```Bash
python Generator.py
```

即可。
