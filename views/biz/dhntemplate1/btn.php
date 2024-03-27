<div align="left" style="padding:-180px; height: auto; width: 100% !important;">
    <table class="table table-bordered table-highlight-head scrolltbody" style="width:100% !important; table-layout:fixed;">
        <thead>
        <tr style="cursor:default;">
            <th class="text-center" width="20">no</th>
            <th class="text-center" width="40">버튼타입</th>
            <th class="text-center" width="50">버튼명</th>
            <th class="text-center" width="100">버튼링크</th>
        </tr>
        </thead>
        <tbody class="table-content" style="cursor: default;" id="btn_tbody">
    </table>
</div>
<style>
    #btn_tbody td {
        word-break:break-all;
    }
</style>
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
                html += "<td align='center'>" + counter + "</td>";
                html += "<td align='center'>" + btn_content[i]["linkTypeName"] + "</td>";
                html += "<td align='center'>" + btn_content[i]["name"] + "</td>";
                html += "<td align='center'></td>";
                html += "</tr>";
            } else if (btn_content[i]["linkType"] == "WL") {
                var linkPc = '&nbsp;';
                if(btn_content[i]["linkPc"]!=null){
                    linkPc = btn_content[i]["linkPc"]
                }
                btn_type = "웹링크";
                html += "<tr>";
                html += "<td style='vertical-align: middle;' align='center' rowspan='2'>" + counter + "</td>";
                html += "<td style='vertical-align: middle;' align='center' rowspan='2'>" + btn_content[i]["linkTypeName"] + "</td>";
                html += "<td style='vertical-align: middle;' align='center' rowspan='2'>" + btn_content[i]["name"] + "</td>";
                html += "<td><label style='vertical-align: middle !important; font-weight: 100;'>Mobile</label><div>" + btn_content[i]["linkMo"] + "</div></td>";
                html += "</tr>";
                html += "<tr id='btn_tr_" + counter + "'>";
                html += "<td><label style='vertical-align: middle !important; font-weight: 100;'>PC(선택)</label><div>" + linkPc + "</div></td>";
                html += "</tr>";
            } else if (btn_content[i]["linkType"] == "AL") {
                    btn_type = "앱링크";
                    html += "<tr>";
                    html += "<td style='vertical-align: middle;' align='center' rowspan='2'>" + counter + "</td>";
                    html += "<td style='vertical-align: middle;' align='center' rowspan='2'>" + btn_content[i]["linkTypeName"] + "</td>";
                    html += "<td style='vertical-align: middle;' align='center' rowspan='2'>" + btn_content[i]["name"] + "</td>";
                    html += "<td><label style='vertical-align: middle !important; font-weight: 100;'>Android</label><div>" + btn_content[i]["linkAnd"] + "</div></td>";
                    html += "</tr>";
                    html += "<tr id='btn_tr_" + counter + "'>";
                    html += "<td><label style='vertical-align: middle !important; font-weight: 100;'>iOS</label><div>" + btn_content[i]["linkIos"] + "</div></td>";
                    html += "</tr>";
            }
            if (i==0) {
                $("#btn_tbody").html(html);
            } else {
                $("#btn_tr_" + i).after(html);
            }
        }
    

</script>