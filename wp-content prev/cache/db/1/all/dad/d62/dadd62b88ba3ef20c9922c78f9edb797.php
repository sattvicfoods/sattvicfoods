
�[<?php exit; ?>a:6:{s:10:"last_error";s:0:"";s:10:"last_query";s:585:"SELECT  t.*, tt.*, tr.object_id, tm.meta_value FROM wp_terms AS t  INNER JOIN wp_term_taxonomy AS tt ON t.term_id = tt.term_id INNER JOIN wp_term_relationships AS tr ON tr.term_taxonomy_id = tt.term_taxonomy_id LEFT JOIN wp_termmeta AS tm ON (t.term_id = tm.term_id AND tm.meta_key = 'order')  WHERE tt.taxonomy IN ('product_type', 'product_visibility', 'product_cat', 'product_tag', 'product_shipping_class', 'pa_blood-type', 'pa_dosha', 'pa_returns', 'pa_shelf-life', 'pa_size') AND tr.object_id IN (106785)  GROUP BY t.term_id, tr.object_id ORDER BY tm.meta_value+0 ASC, t.name ASC ";s:11:"last_result";a:4:{i:0;O:8:"stdClass":11:{s:7:"term_id";s:3:"255";s:4:"name";s:5:"130 g";s:4:"slug";s:5:"130-g";s:10:"term_group";s:1:"0";s:16:"term_taxonomy_id";s:3:"255";s:8:"taxonomy";s:7:"pa_size";s:11:"description";s:0:"";s:6:"parent";s:1:"0";s:5:"count";s:1:"1";s:9:"object_id";s:6:"106785";s:10:"meta_value";N;}i:1;O:8:"stdClass":11:{s:7:"term_id";s:3:"177";s:4:"name";s:8:"featured";s:4:"slug";s:8:"featured";s:10:"term_group";s:1:"0";s:16:"term_taxonomy_id";s:3:"177";s:8:"taxonomy";s:18:"product_visibility";s:11:"description";s:0:"";s:6:"parent";s:1:"0";s:5:"count";s:2:"10";s:9:"object_id";s:6:"106785";s:10:"meta_value";N;}i:2;O:8:"stdClass":11:{s:7:"term_id";s:2:"50";s:4:"name";s:8:"variable";s:4:"slug";s:8:"variable";s:10:"term_group";s:1:"0";s:16:"term_taxonomy_id";s:2:"50";s:8:"taxonomy";s:12:"product_type";s:11:"description";s:0:"";s:6:"parent";s:1:"0";s:5:"count";s:2:"74";s:9:"object_id";s:6:"106785";s:10:"meta_value";N;}i:3;O:8:"stdClass":11:{s:7:"term_id";s:3:"238";s:4:"name";s:18:"Gluten-Free Snacks";s:4:"slug";s:9:"gf_snacks";s:10:"term_group";s:1:"0";s:16:"term_taxonomy_id";s:3:"238";s:8:"taxonomy";s:11:"product_cat";s:11:"description";s:0:"";s:6:"parent";s:3:"139";s:5:"count";s:1:"2";s:9:"object_id";s:6:"106785";s:10:"meta_value";s:1:"0";}}s:8:"col_info";a:12:{i:0;O:8:"stdClass":13:{s:4:"name";s:7:"term_id";s:7:"orgname";s:7:"term_id";s:5:"table";s:1:"t";s:8:"orgtable";s:8:"wp_terms";s:3:"def";s:0:"";s:2:"db";s:6:"sfoods";s:7:"catalog";s:3:"def";s:10:"max_length";i:3;s:6:"length";i:20;s:9:"charsetnr";i:63;s:5:"flags";i:32801;s:4:"type";i:8;s:8:"decimals";i:0;}i:1;O:8:"stdClass":13:{s:4:"name";s:4:"name";s:7:"orgname";s:4:"name";s:5:"table";s:1:"t";s:8:"orgtable";s:8:"wp_terms";s:3:"def";s:0:"";s:2:"db";s:6:"sfoods";s:7:"catalog";s:3:"def";s:10:"max_length";i:18;s:6:"length";i:800;s:9:"charsetnr";i:246;s:5:"flags";i:1;s:4:"type";i:253;s:8:"decimals";i:0;}i:2;O:8:"stdClass":13:{s:4:"name";s:4:"slug";s:7:"orgname";s:4:"slug";s:5:"table";s:1:"t";s:8:"orgtable";s:8:"wp_terms";s:3:"def";s:0:"";s:2:"db";s:6:"sfoods";s:7:"catalog";s:3:"def";s:10:"max_length";i:9;s:6:"length";i:800;s:9:"charsetnr";i:246;s:5:"flags";i:1;s:4:"type";i:253;s:8:"decimals";i:0;}i:3;O:8:"stdClass":13:{s:4:"name";s:10:"term_group";s:7:"orgname";s:10:"term_group";s:5:"table";s:1:"t";s:8:"orgtable";s:8:"wp_terms";s:3:"def";s:0:"";s:2:"db";s:6:"sfoods";s:7:"catalog";s:3:"def";s:10:"max_length";i:1;s:6:"length";i:10;s:9:"charsetnr";i:63;s:5:"flags";i:32769;s:4:"type";i:8;s:8:"decimals";i:0;}i:4;O:8:"stdClass":13:{s:4:"name";s:16:"term_taxonomy_id";s:7:"orgname";s:16:"term_taxonomy_id";s:5:"table";s:2:"tt";s:8:"orgtable";s:16:"wp_term_taxonomy";s:3:"def";s:0:"";s:2:"db";s:6:"sfoods";s:7:"catalog";s:3:"def";s:10:"max_length";i:3;s:6:"length";i:20;s:9:"charsetnr";i:63;s:5:"flags";i:32801;s:4:"type";i:8;s:8:"decimals";i:0;}i:5;O:8:"stdClass":13:{s:4:"name";s:7:"term_id";s:7:"orgname";s:7:"term_id";s:5:"table";s:2:"tt";s:8:"orgtable";s:16:"wp_term_taxonomy";s:3:"def";s:0:"";s:2:"db";s:6:"sfoods";s:7:"catalog";s:3:"def";s:10:"max_length";i:3;s:6:"length";i:20;s:9:"charsetnr";i:63;s:5:"flags";i:32801;s:4:"type";i:8;s:8:"decimals";i:0;}i:6;O:8:"stdClass":13:{s:4:"name";s:8:"taxonomy";s:7:"orgname";s:8:"taxonomy";s:5:"table";s:2:"tt";s:8:"orgtable";s:16:"wp_term_taxonomy";s:3:"def";s:0:"";s:2:"db";s:6:"sfoods";s:7:"catalog";s:3:"def";s:10:"max_length";i:18;s:6:"length";i:128;s:9:"charsetnr";i:246;s:5:"flags";i:1;s:4:"type";i:253;s:8:"decimals";i:0;}i:7;O:8:"stdClass":13:{s:4:"name";s:11:"description";s:7:"orgname";s:11:"description";s:5:"table";s:2:"tt";s:8:"orgtable";s:16:"wp_term_taxonomy";s:3:"def";s:0:"";s:2:"db";s:6:"sfoods";s:7:"catalog";s:3:"def";s:10:"max_length";i:0;s:6:"length";i:4294967295;s:9:"charsetnr";i:246;s:5:"flags";i:4113;s:4:"type";i:252;s:8:"decimals";i:0;}i:8;O:8:"stdClass":13:{s:4:"name";s:6:"parent";s:7:"orgname";s:6:"parent";s:5:"table";s:2:"tt";s:8:"orgtable";s:16:"wp_term_taxonomy";s:3:"def";s:0:"";s:2:"db";s:6:"sfoods";s:7:"catalog";s:3:"def";s:10:"max_length";i:3;s:6:"length";i:20;s:9:"charsetnr";i:63;s:5:"flags";i:32801;s:4:"type";i:8;s:8:"decimals";i:0;}i:9;O:8:"stdClass":13:{s:4:"name";s:5:"count";s:7:"orgname";s:5:"count";s:5:"table";s:2:"tt";s:8:"orgtable";s:16:"wp_term_taxonomy";s:3:"def";s:0:"";s:2:"db";s:6:"sfoods";s:7:"catalog";s:3:"def";s:10:"max_length";i:2;s:6:"length";i:20;s:9:"charsetnr";i:63;s:5:"flags";i:32769;s:4:"type";i:8;s:8:"decimals";i:0;}i:10;O:8:"stdClass":13:{s:4:"name";s:9:"object_id";s:7:"orgname";s:9:"object_id";s:5:"table";s:2:"tr";s:8:"orgtable";s:21:"wp_term_relationships";s:3:"def";s:0:"";s:2:"db";s:6:"sfoods";s:7:"catalog";s:3:"def";s:10:"max_length";i:6;s:6:"length";i:20;s:9:"charsetnr";i:63;s:5:"flags";i:32801;s:4:"type";i:8;s:8:"decimals";i:0;}i:11;O:8:"stdClass":13:{s:4:"name";s:10:"meta_value";s:7:"orgname";s:10:"meta_value";s:5:"table";s:2:"tm";s:8:"orgtable";s:11:"wp_termmeta";s:3:"def";s:0:"";s:2:"db";s:6:"sfoods";s:7:"catalog";s:3:"def";s:10:"max_length";i:1;s:6:"length";i:4294967295;s:9:"charsetnr";i:246;s:5:"flags";i:16;s:4:"type";i:252;s:8:"decimals";i:0;}}s:8:"num_rows";i:4;s:10:"return_val";i:4;}