/* Inicialización en español para la extensión 'UI date picker' para jQuery. */
/* Traducido por Vester (xvester@gmail.com). */
myjQuery = (typeof myjQuery != 'undefined' ) ? myjQuery : jQuery;myjQuery(function(){ (function($) {
	$.datepicker.regional['es_ES'] = {
		closeText: 'Cerrar',
		prevText: '&#x3C;Ant',
		nextText: 'Sig&#x3E;',
		currentText: 'Hoy',
		monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
		'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
		monthNamesShort: ['ene','feb','mar','abr','may','jun',
		'jul','ogo','sep','oct','nov','dic'],
		dayNames: ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'],
		dayNamesShort: ['dom','lun','mar','mié','juv','vie','sáb'],
		dayNamesMin: ['D','L','M','X','J','V','S'],
		weekHeader: 'Sm',
		dateFormat: 'dd/mm/yy',
		firstDay: 1,
		isRTL: false,
		showMonthAfterYear: false,
		yearSuffix: ''};	
}(myjQuery)); });
