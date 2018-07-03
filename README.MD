## 🚲Dplayer-dans-php

适用于超低频访问的站点~~比如我~~，高频请移步✈[DIYgod的重型后端](https://github.com/MoePlayer/DPlayer-node) 233

PS:照着PHP教程xjb乱搞出来的，本着能用就行的原则，先不管那么多😝

## 环境

目前可以正常使用的环境（其他环境未测试）：

* PHP7.0 & Dplayer v1.6.1

* PHP5.6 & Dplayer v1.22.2

## 使用方法

先克隆到服务器

```
git clone https://github.com/menhood/Dplayer-dans-php
```

然后重命名文件夹，比如`dplayer`，并改变权限(反正`dans`文件夹都必须要写入权限，图省事全搞成777了😜 虽然可能会炸)

```
mv Dplayer-dans-php dplayer

chmod 777 -R dplayer
```

移动到网站根目录(根据自己网站的路径来，想放哪里放哪里😋)

```
mv dplayer /web/
```

接下来在播放器设置里面就可以调用啦，域名+你放置的目录。比如：`https://api.menhood.wang/dplayer`、`https://api.menhood.wang/dphpv2`……（这两个是我自建的，日常抽风）

## 注意事项
我用的wordpress的插件由于太久没有更新所以是`1.6.1`版本，可以直接用`dplayer`接口，新版的`Dplayer1.22.2`调用接口为`v2`,两个版本返回的数据不一样。

最近DIYgod重构了后端，而且截止今天~~2018-7-3~~Dplayer已经是`v1.23.0`版本了

如果使用新版，建议使用最新的前后端，地址：[Dplayer](https://github.com/MoePlayer/DPlayer)  [Dplayer-node(后端)](https://github.com/MoePlayer/DPlayer-node)

DPlayer-node是使用Docker搭建的，docker版本应该是>=1.12的，可怜我搬瓦工的机器为了ss只能用centos6的系统，升级docker得升级内核，太麻烦了，残念……

关于弹幕管理：由于技术太渣，没有搞出来后台~~主要是怕出事情~~，所以可以手动ftp修改~~打死~~，`dans`文件夹下的文件名对应相应播放器`GET`的`id`

更新日期：2018-07-03 Menhood