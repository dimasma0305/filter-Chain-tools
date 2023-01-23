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

![img](https://dqgom7v7dl.feishu.cn/space/api/box/stream/download/asynccode/?code=NjYwOGE5N2IwNjUxOTQ1ZjViMmQ1MTg0ODhhMWMyZmVfcXRBZFZseWFFaEtHbmVOZGV5TlBSTjFKVnppTEE3R3dfVG9rZW46Ym94Y25ib25qbHVoQmE1clk1YU5OV240RkF2XzE2NzQ0OTIxMzg6MTY3NDQ5NTczOF9WNA)

根据对应环境选择对应的字符集合：

```Bash
iconv -l
```

![img](https://dqgom7v7dl.feishu.cn/space/api/box/stream/download/asynccode/?code=MjU2MTZmMTcyYmJjOTkzY2ZlMGM0MGU3MGRiYjk4OTVfM05CeldJeERESmkyWVdibmpxZGZMZGo2SFlnbVl1Q2RfVG9rZW46Ym94Y25FUXZYeVdQUXJkemNWRnliV2lPdU80XzE2NzQ0OTIxMzg6MTY3NDQ5NTczOF9WNA)

在Fuzzer.php中设置好参数：

![img](https://dqgom7v7dl.feishu.cn/space/api/box/stream/download/asynccode/?code=YWNiNDRhYTEwN2UxNzExZDcwN2VkNzg5ZTZiMDA5YTVfa2lCRUVIdGt6enZQWjVGQ2FDRlRHS2F3eFAyeGtaNGhfVG9rZW46Ym94Y25WRWx5TVliQVJEY1dxY201NFp3R2ZmXzE2NzQ0OTIxMzg6MTY3NDQ5NTczOF9WNA)

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

![img](https://dqgom7v7dl.feishu.cn/space/api/box/stream/download/asynccode/?code=OTcxNTM4MzVhOGRmNmNjMmE1NGE1ZDczN2JkOTY1OTZfcHVzM1JJazQ4MkZQcWFnazUwV3RBZ3BLdFFZU3dxc3JfVG9rZW46Ym94Y252YVJQekxmWW1zZVpCVjFuRXJLcjliXzE2NzQ0OTIxMzg6MTY3NDQ5NTczOF9WNA)

当然您也可以根据项目原理自己生成。

如果您使用第二种模式，项目也准备了一份Fuzz好的单字母字典在dictionary.py中：

![img](https://dqgom7v7dl.feishu.cn/space/api/box/stream/download/asynccode/?code=NWY4ZWVmYTM3YTdmNzQ2ZTNlNzg2NDI0ZDBlYzI1MGNfbW5SQzdpSEFZY2tTZVRwTTFBaDJnMTlxdGFrSWFqckRfVG9rZW46Ym94Y24wRjRsZnVDQlhDM1BVV0YySHpCb05oXzE2NzQ0OTIxMzg6MTY3NDQ5NTczOF9WNA)

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