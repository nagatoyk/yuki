var INF={
	url:'http://kloli.tk/',
	// 主标题
	n:'小熊\'博客',	//name
	// 副标题
	t:'一个90后骚年',
	// 头像地址
	av:'//ww3.sinaimg.cn/bmiddle/64909e84jw1esz30joqqrj20h40fijsk.jpg',
	// 默认用户名
	u:[
		'镜花水月',
		'凉宫长门'
	],
	// 分类
	c:{
		nichijou:['日常','我是分类简介'],
		photo:['照片','我是分类简介'],
		video:['视频','各类视频'],
		feel:['情感','我是分类简介'],
		update:['日志','更新记录'],
		hide:['我是隐藏的分类',null]
		/*无分类简介时，当前分类将不在首页NAV显示！*/
	},
	/*去掉这里的注释可以在 nav 上添加一项导航*/
	p:[
	 	//{
	 	//	name:'VSCO',
	 	//	url:'#!vsco'
	 	//}
	],
	/*设置 wb_name 可以在使用多说评论时得到私信提示功能*/
	wb_name:'凉宫长门'
};
var DS_cfg={
	/*此处如留空将调用自身评论*/
	id:'你在多说的网站ID'
};

var hitokoto = {
	api:'http://hitoapi.cc/s/',
	/*是否将一言填入副标题,默认关闭，开启为1/true，一言将覆盖原标题内容*/
	t:1	
}