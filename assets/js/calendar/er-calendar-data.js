async function er_call_to_ajax_function(data, contentType) {
  try {
    response = await jQuery.ajax({
      url: er_calendar_ajax.ajaxurl,
      type: 'post',
      //contentType: 'application/json',
      data,
      beforeSend: function() {
        uiSpinner(contentType);
      },
    });

    return JSON.parse(response);
  } catch (error) {
    console.log("ERROR: ", error);
  }
}

async function er_get_calendar_data() {
  try {
    const eventsData = await er_call_to_ajax_function({
      action: "er_get_calendar",
      data: {},
    }, "event");
    const events = [];
    
    for(const event of eventsData) {
      const dateParts = event.date.split("/");
      const date = new Date(+dateParts[2], dateParts[1] - 1, +dateParts[0]);
      events.push({
        title: event.title,
        date,
        link: event.link,
      });
    }

    console.log("EVENTS", events);

    jQuery("#er-calendar").MEC({
      calendar_link: "https://fvkarting.com.ve/eventos",
      from_monday: true,
      events,
      onMonthChanged: er_onMonthChanged,
    });
  } catch (error) {
    console.log("ERROR: ", error);
  }
}

async function er_onMonthChanged (month, year) {
  console.log("MONTH CHANGED", month + 1, year);
  const month_str = (month + 1).toString();
  const monthFormated = month + 1 < 10 ? "0" + month_str : month_str;

  const response = await er_call_to_ajax_function({
    action: "er_get_event_filter",
    month: monthFormated,
    year,
  });

  console.log("EVENTS FILTERED", response.data);
  event_list(response.data);
}

jQuery(document).ready(_ => {
  jQuery("#er-calendar").ready(_ => {
    er_get_calendar_data();
  });

  let events = [];

  jQuery("#er-calendar").on("click touchstart", ".a-date", async function(e){
    const data = jQuery(this).data('event');

    if (!!data) {
      const dateToFilter = (new Date(data.date).toLocaleDateString('en-GB')).split("/");
      console.log("CLICKEA", dateToFilter);
      response = await er_call_to_ajax_function({
        action: "er_get_event_filter",
        day: dateToFilter[0],
        month: dateToFilter[1],
        year: dateToFilter[2],
      }, "event");
      events = response.data;
    
      console.log("EVENTS FILTERED", events, events.length);
      event_list(events, true);
    } else {
      empty_list();
    }
  });
});
