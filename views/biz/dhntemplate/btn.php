<div class="table_list">
    <table>
	    <colgroup>
		    <col width="10%">
		    <col width="15%">
		    <col width="">
		    <col width="10%">
		    <col width="20%">
	    </colgroup>
        <thead>
        <tr>
            <th>no</th>
            <th>버튼타입</th>
            <th>버튼명</th>
            <th colspan="2">버튼링크</th>
        </tr>
        </thead>
        <tbody class="table-content" style="cursor: default;" id="btn_tbody">
    </table>
</div>

<script type="text/javascript">
    
        var buttons = '<?=$rs->tpl_button?>';
        var btn = buttons.replace(/&quot;/gi, "\"");
        var btn_content = JSON.parse(btn);

        for (var i = 0; i < btn_content.length; i++) {
            var counter = i + 1;
            var html = "";
			if(btn_content[i]["linkType"]=="DS") btn_content[i]["linkTypeName"] = "<?=$this->Biz_model->buttonName['DS']?>";
			else if(btn_content[i]["linkType"]=="WL") btn_content[i]["linkTypeName"] = "<?=$this->Biz_model->buttonName['WL']?>";
			else if(btn_content[i]["linkType"]=="AL") btn_content[i]["linkTypeName"] = "<?=$this->Biz_model->buttonName['AL']?>";
			else if(btn_content[i]["linkType"]=="BK") btn_content[i]["linkTypeName"] = "<?=$this->Biz_model->buttonName['BK']?>";
			else if(btn_content[i]["linkType"]=="MD") btn_content[i]["linkTypeName"] = "<?=$this->Biz_model->buttonName['MD']?>";
            if (btn_content[i]["linkType"] == "DS" || btn_content[i]["linkType"] == "BK" || btn_content[i]["linkType"] == "MD") {
                html += "<tr id='btn_tr_" + counter + "'>";
                html += "<td>" + counter + "</td>";
                html += "<td>" + btn_content[i]["linkTypeName"] + "</td>";
                html += "<td>" + btn_content[i]["name"] + "</td>";
                html += "<td></td>";
                html += "</tr>";
            } else if (btn_content[i]["linkType"] == "WL") {
                var linkPc = '&nbsp;';
                if(btn_content[i]["linkPc"]!=null){
                    linkPc = btn_content[i]["linkPc"]
                }
                btn_type = "웹링크";
                html += "<tr>";
                html += "<td rowspan='2'>" + counter + "</td>";
                html += "<td rowspan='2'>" + btn_content[i]["linkTypeName"] + "</td>";
                html += "<td rowspan='2'>" + btn_content[i]["name"] + "</td>";
                html += "<td>Mobile</td>";
                html += "<td>" + btn_content[i]["linkMo"] + "</td>";
                html += "</tr>";
                html += "<tr id='btn_tr_" + counter + "'>";
                html += "<td>PC(선택)</td>";
                html += "<td>" + linkPc + "</td>";
                html += "</tr>";
            } else if (btn_content[i]["linkType"] == "AL") {
                    btn_type = "앱링크";
                    html += "<tr>";
                    html += "<td rowspan='2'>" + counter + "</td>";
                    html += "<td rowspan='2'>" + btn_content[i]["linkTypeName"] + "</td>";
                    html += "<td rowspan='2'>" + btn_content[i]["name"] + "</td>";
                    html += "<td>Android</td>";
                    html += "<td>" + btn_content[i]["linkAnd"] + "</td>";
                    html += "</tr>";
                    html += "<tr id='btn_tr_" + counter + "'>";
                    html += "<td>iOS</td>";
                    html += "<td>" + btn_content[i]["linkIos"] + "</td>";
                    html += "</tr>";
            }
            if (i==0) {
                $("#btn_tbody").html(html);
            } else {
                $("#btn_tr_" + i).after(html);
            }
        }
    

</script>