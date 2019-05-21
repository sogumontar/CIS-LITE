
$.fullCalendar.locale("cs", {
	buttonText: {
		month: "Měsíc",
		week: "Týden",
		day: "Den",
		list: "Agenda"
	},
	allDayText: "Celý den",
	eventLimitText: function(n) {
		return "+další: " + n;
	},
	noEventsMessage: "Žádné akce k zobrazení"
});
