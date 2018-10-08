<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Prueba Miguel Ruiz</title>
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/bootswatch/3.3.5/custom/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="http://timelib.890m.com/css/style.css" />
<link rel="stylesheet" type="text/css" href="http://licklock.96.lt/marker-extend.css" />

    <link rel="stylesheet" type="text/css" href="/js/jquery-easyui/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="/js/jquery-easyui/themes/icon.css">
    <link rel="stylesheet" type="text/css" href="/js/jquery-easyui/demo/demo.css">
    <script type="text/javascript" src="/js/jquery-easyui/jquery.min.js"></script>
    <script type="text/javascript" src="/js/jquery-easyui/jquery.easyui.min.js"></script>


<script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/js/bootstrap.min.js"></script>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js"></script>


<script src="http://licklock.96.lt/L.Editcircle.js"></script>

<script>
    //global variables
    var Circle;
    var marker=[];
    var map;
    $(function() {
        //This code is for simple Map
        map = L.map('map').setView([4.6657,-74.0939], 12);
        // add an OpenStreetMap tile layer
        L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
        attribution: 'ALKANZA'
        }).addTo(map);
        //marker = L.marker([4.6657,-74.0939]).addTo(map);  
        var rad = 500; /*radius in meters*/
        Circle = new L.Editcircle([4.6657,-74.0939], rad,  {
        color: 'red',
        fillColor: '#f03',
        fillOpacity: 0.5
        });
        map.addLayer(Circle);

        Circle.on('edit', function () {
          cir();
            });
        Circle.on('centerchange', function () {
            cir();
            });
        Circle.on('radiuschange', function () {
             cir();
            });

         map.on('click', onMapClick);
              
              cargaCentros();
              cargaCalculos();


        $("#guarda").click(function(){
            var nombre = prompt("Escriba el nombre con el que se guardará este cálculo", "");
            if (nombre == null || nombre == "") {
                //txt = "User cancelled the prompt.";
            } else {
                $.ajax({
                  url: "/centros?oper=guarda&nombre="+nombre+"&lat="+Circle.getLatLng().lat+"&lon="+Circle.getLatLng().lng+'&radio='+Circle.getRadius()+'&valor='+valForm,
                  cache: false
                }).done(function( html ) {
                    cargaCalculos();
                });
            }
        });

    });

    var popup = L.popup();

    function onMapClick(e) {

        if ($("#btnNew").hasClass("l-btn-selected")){
            var nombre = prompt("Escriba el nombre del centro médico", "");
            if (nombre == null || nombre == "") {
                //txt = "User cancelled the prompt.";
            } else {
                $.ajax({
                  url: "/centros?oper=insert&nombre="+nombre+"&lat="+e.latlng.lat+"&lon="+e.latlng.lng,
                  cache: false
                }).done(function( html ) {
                    cargaCentros();
                });
            }
        }
    }



   function cargaCentros(){
        clearAll();
        balanceos=[];
        desbalanceados=[];
        balanceados=[];
        $.getJSON( "/centros?oper=select", function( data ) {
          var li="";
          var img="";
          $.each( data, function( val ) {
            img="balance";
            if (data[val].balanceado==0)
                img="unbalance";
            balanceos.push(data[val].balanceado);
            li=li+( "<li id='li" + data[val].id_centro + "'>" + data[val].nombre + "<a href='javascript:void(0);' onClick='delCentro(" + data[val].id_centro + ");'><img src='/images/del.png' title='Eliminar'></a>  <a href='javascript:void(0);' onClick='balance(" + data[val].id_centro + ","+data[val].balanceado+");'><img src='/images/"+img+".png' title='Balancear / Desbalancear'></a></li>" );
            marker.push(L.marker([data[val].lat,data[val].lon]).bindPopup( data[val].nombre ).addTo(map));

              });
         $(".centros").html(li);
         cir();
        });
   }

   function clearAll(){
        for (var i=0;i<marker.length;i++)
            map.removeLayer(marker[i]);
        marker=[];
   }

   function cargaCalculos(){
        $.getJSON( "/centros?oper=calculos", function( data ) {
          var li="";
          var img="";
          $.each( data, function( val ) {
            li=li+( "<li id='liC" + data[val].id_calculo + "'>" + data[val].nombre + "<a href='javascript:void(0);' onClick='delCalculo(" + data[val].id_calculo + ");'><img src='/images/del.png' title='Eliminar'></a>  <a href='javascript:void(0);' onClick='loadC(" + data[val].lat + ","+data[val].lon+"," + data[val].radio + "," + data[val].valor + ");'><img src='/images/load.png' title='Mostrar'></a></li>" );
              });
         $(".calculos").html(li);
        });
   }

    function delCentro(id_centro){
        $.ajax({
              url: "/centros?oper=delete&id_centro="+id_centro,
              cache: false
            }).done(function( html ) {
                $("#li"+id_centro).fadeOut();
                cargaCentros();
            });
    }

    function delCalculo(id_calculo){
        $.ajax({
              url: "/centros?oper=deleteCal&id_calculo="+id_calculo,
              cache: false
            }).done(function( html ) {
                $("#liC"+id_calculo).fadeOut();
            });
    }

    var balanceos=[];
    var desbalanceados=[];
    var balanceados=[];
    var valForm=0;

    function cir() {
        var cc=0;
        $("#radio").html(parseInt(Circle.getRadius()));
        desbalanceados=[];
        balanceados=[];
        for (var i=0;i<marker.length;i++){
            var  mk = marker[i].getLatLng();
            if (distancia(Circle.getLatLng(),mk)<=Circle.getRadius()) {
                marker[i].setIcon(inside());
                cc=cc+1;
                if (balanceos[i]==0)
                    desbalanceados.push(mk);
                else
                    balanceados.push(mk);
            }else{
                marker[i].setIcon(outside());
            }
        }
        $("#desbalanceo").html(calculoDesbalance());
        $("#centros").html(cc);
    }
    function inside() {
        var CustomIcon = L.Icon.extend({
            options: {
                iconUrl: '/images/pin1.png',
                iconSize: new L.Point(20, 33),
                opacity: 1,
                iconAnchor: new L.Point(10, 33),
                popupAnchor: new L.Point(0, 0)
            }
        });
        var icon = new CustomIcon();
        return icon;
    }
    function outside() {
        var CustomIcon = L.Icon.extend({
            options: {
                iconUrl: '/images/pin2.png',
                iconSize: new L.Point(20, 33),
                opacity: 1,
                iconAnchor: new L.Point(10, 33),
                popupAnchor: new L.Point(0, -18)
            }
        });
        var icon = new CustomIcon();
        return icon;
    }

    function balance(id_centro,balance){
        $.ajax({
          url: "/centros?oper=balance&id_centro="+id_centro+"&balance="+balance,
          cache: false
        }).done(function( html ) {
            cargaCentros();
        });
    }

    function loadC(lat,lon,radio,valor){


        valForm=valor;

        map.removeLayer(Circle);
        Circle.setRadius(radio);
        Circle.setLatLng([lat,lon]);
        Circle = new L.Editcircle([lat,lon], radio,  {
            color: 'red',
            fillColor: '#f03',
            fillOpacity: 0.5
        });
        map.addLayer(Circle);
        Circle.on('edit', function () {
          cir();
            });
        Circle.on('centerchange', function () {
            cir();
            });
        Circle.on('radiuschange', function () {
             cir();
            });

        cir();
        

    }

    function calculoDesbalance(){
        var result=0;
        for (var i=0;i<desbalanceados.length;i++){
            for (var j=0;j<balanceados.length;j++){
                result=result+Math.abs(distancia(desbalanceados[i],Circle.getLatLng()) - distancia(balanceados[j],Circle.getLatLng()));
            }
        }
        valForm=result.toFixed(2)
        return valForm;
    }

    function distancia(p1, p2) {
      var R = 6378137; // Earth’s mean radius in meter
      var dLat = radd(p2.lat - p1.lat);
      var dLong = radd(p2.lng - p1.lng);
      var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
        Math.cos(radd(p1.lat)) * Math.cos(radd(p2.lat)) *
        Math.sin(dLong / 2) * Math.sin(dLong / 2);
      var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
      var d = R * c;
      return d; // returns the distance in meter
    }

    var radd = function(x) {
      return x * Math.PI / 180;
    };
</script>

</head>
<body class="easyui-layout">
    <div data-options="region:'north',border:false" style="height:60px;padding:10px"><img src="/images/logo.jpg"><h1>DESBALANCE DE DISTANCIA</h1></div>
    <div data-options="region:'west',split:true,title:'Centros Médicos'" style="width:200px;padding:10px;">
        <ul class="centros">
        </ul>
    </div>
    <div data-options="region:'east',split:true,title:'Cálculos Guardados'" style="width:200px;padding:10px;">
        <ul class="calculos">
        </ul>
    </div>
    <div data-options="region:'south',border:false" style="height:50px;background:#A9FACD;padding:10px;">Desarrollado por: Miguel E. Ruiz León</div>
    <div data-options="region:'center',title:'Mapa'">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'north',split:true" style="height:50px">
                <div style="padding:5px;">
                    <a href="#" id="btnNew" class="easyui-linkbutton" data-options="iconCls:'icon-add',toggle:true">Agregar Centro Médico</a>
                    <a href="#" id="guarda" class="easyui-linkbutton" data-options="iconCls:'icon-save'">Guardar Cálculo</a>
                </div>
            </div>
            <div data-options="region:'center'"><div id="map"></div></div>
            <div data-options="region:'south',split:true" style="height:50px">
                <div style="padding:5px;">
                    <table border="1" class="tblDat">
                        <tr>
                            <td><p>Radio: <span id="radio">500</span></p></td>
                            <td><p>Centros en el rango: <span id="centros">0</span></p></td>
                            <td><p>Desbalanceo: <span id="desbalanceo">0</span></p></td>
                        </tr>
                    </table>
                    
                </div>
            </div>
        </div>
    </div>



</body>
</html>