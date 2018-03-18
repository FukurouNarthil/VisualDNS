1. 行（row）必须放在.container class内，一个row代表1行，1行内被分为12列。
2. col代表列，col-xx-y中的y代表当前元素所占的列数。xx的值可以为四种：lg、md、sm、xs。分别代表在四种尺寸宽度下的布局样式，四种尺寸宽度为：lg>=1200px、md>=992px、sm>=768px、xs<768px
3. lg后面的offset指的是偏移量，即在lg的布局下，当前标签加上对于列数的左边外距(margin-left)，col-lg-2占了两个列。
4. carousel轮播插件：interval轮播的时间间隔，wrap：true使轮播连续循环。
5. 导航栏注册和登录的字体图标：<li><a href="#"><span class="glyphicon glyphicon-user"></span> 注册</a></li>
					<li><a href="#"><span class="glyphicon glyphicon-log-in"></span> 登录</a></li>
6. 