// global function

// 获取输入参数row string
function getInputParamRowString(id) {
	var str = "<tr id='" + id + "'>" +
				  "<td><input type='text' placeholder='必填' name='input_name[]'></td>" + 
				  "<td><input type='text' placeholder='选填' name='input_sample[]'></td>" + 
				  "<td><input type='text' placeholder='选填' name='input_detail[]'></td>" + 
				  "<td>string</td>" +
				  "<td>" + 
				  "	 <a class='delete_param_button'><i class='icon-minus'></i></a>" + 
				  "</td>" + 
		      "</tr>";
	return str;
}

function paramsStringFromHash(params) {
	var size = Object.size(params)
	var str = ""
	if (size > 0) {
		var i = 0;
		for (name in params) {
			str = str + name + "=" + encodeURIComponent(params[name]);
			if (i < size - 1) {
				str = str + "&"
			}
			i++;
		}
	}
	return str;
}

function keys(obj) {
    var keys = [];

    for(var key in obj)
    {
        if(obj.hasOwnProperty(key))
        {
            keys.push(key);
        }
    }

    return keys;
}

function md5ParamStringFromHash(params) {
	var size = Object.size(params)
	var str = ""
	if (size > 0) {
		var names = keys(params);
		names.sort();
		for (var i=0; i<Object.size(names); i++) {
			var name = names[i];
			str = str + name + "=" + params[name];
			if (i < size - 1) {
				str = str + "&"
			}
		}
	}
	return $.md5(str);
}


function paramsFromParamTable(rows) {
	result = {}
	rows.each(function() {
		var name = $(this).find("td:first input").val();
		var value = $(this).find("td:eq(1) input").val();
		result[name] = value
	})
	return result
}

function addTableRow(table, rowStringFunc) {
	last_id = table.find("tbody tr:last").attr("id"); 
	new_id = null;
	if (last_id) {
		new_id = parseInt(last_id) + 1;
	} else {
		new_id = 0;
	}
	var row = $(rowStringFunc(new_id));
	row.appendTo(table.find("tbody"));
	row.find("a").click(function() {
		deleteTableRow($(this).parent().parent());
	})
	return row
}

function deleteTableRow(row) {
	row.remove();
}

function locationFromURL(url) {
	var parser = document.createElement('a');
	parser.href = url;
    return parser;
}

function paramsFromURL(url) {
	var parser = document.createElement('a');
	parser.href = url;
    var nvpair = {};
    var qs = parser.search.replace('?', '');
    var pairs = qs.split('&');
    $.each(pairs, function(i, v){
      var pair = v.split('=');
      nvpair[pair[0]] = decodeURIComponent(pair[1]);
    });
    return nvpair;
}

function deformatJson(str) {
	s = str.replace(/\s/g, ""); 
	return s;
}

Object.size = function(obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
};

function classType(obj) {
    var type = Object.prototype.toString.call(obj).slice(8, -1);
    return type
}

function is(type, obj) {
	var clas = classType(obj)
    return obj !== undefined && obj !== null && clas === type;
}

function format(txt,compress/*是否为压缩模式*/){/* 格式化JSON源码(对象转换为JSON文本) */  
    var indentChar = '    ';   
    if(/^\s*$/.test(txt)){   
        alert('数据为空,无法格式化! ');   
        return;   
    }   
    try{var data=eval('('+txt+')');}   
    catch(e){   
        alert('数据源语法错误,格式化失败! 错误信息: '+e.description,'err');   
        return txt;   
    };   
    var draw=[],last=false,This=this,line=compress?'':'\n',nodeCount=0,maxDepth=0;   
       
    var notify=function(name,value,isLast,indent/*缩进*/,formObj){   
        nodeCount++;/*节点计数*/  
        for (var i=0,tab='';i<indent;i++ )tab+=indentChar;/* 缩进HTML */  
        tab=compress?'':tab;/*压缩模式忽略缩进*/  
        maxDepth=++indent;/*缩进递增并记录*/  
        if(value&&value.constructor==Array){/*处理数组*/  
            draw.push(tab+(formObj?('"'+name+'":'):'')+'['+line);/*缩进'[' 然后换行*/  
            for (var i=0;i<value.length;i++)   
                notify(i,value[i],i==value.length-1,indent,false);   
            draw.push(tab+']'+(isLast?line:(','+line)));/*缩进']'换行,若非尾元素则添加逗号*/  
        }else   if(value&&typeof value=='object'){/*处理对象*/  
                draw.push(tab+(formObj?('"'+name+'":'):'')+'{'+line);/*缩进'{' 然后换行*/  
                var len=0,i=0;   
                for(var key in value)len++;   
                for(var key in value)notify(key,value[key],++i==len,indent,true);   
                draw.push(tab+'}'+(isLast?line:(','+line)));/*缩进'}'换行,若非尾元素则添加逗号*/  
            }else{   
                    if(typeof value=='string')value='"'+value+'"';   
                    draw.push(tab+(formObj?('"'+name+'":'):'')+value+(isLast?'':',')+line);   
            };   
    };   
    var isLast=true,indent=0;   
    notify('',data,isLast,indent,false);   
    return draw.join('');   
}  
