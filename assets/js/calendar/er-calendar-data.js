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

async function er_get_events_by_championship () {
  console.log("CHAMPIONSHIP");
  response = await er_call_to_ajax_function({
    action: "er_get_event_filter",
    orderBy: "championship",
  }, "event");
  const events = response.data;

  console.log("EVENTS CHAMPIONSHIP", events);
  event_list(events, true, "championship");
};

jQuery(document).ready(_ => {
  jQuery("#er-calendar").ready(_ => {
    if (jQuery("#er-calendar").length > 0) er_get_calendar_data();
  });

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
      const events = response.data;
    
      console.log("EVENTS FILTERED", events);
      event_list(events, true);
    } else {
      empty_list();
    }
  });

  jQuery("#er-search-event").on("input", async function (e){
    const input = (e.target.value).toLowerCase();

    if (!!input) {
      response = await er_call_to_ajax_function({
        action: "er_get_event_filter",
        search: input,
      }, "event");
      const events = response.data;

      console.log("EVENTS SEARCHED", events);
      event_list(events);
    } else {
      empty_list();
    }
  });

  jQuery(".er-event-championship").ready(async _ => {
    if (jQuery(".er-event-championship").length <= 0) return;

    await er_get_events_by_championship();
  });

  jQuery("#er-search-championship").on("input", async function (e){
    const search = (e.target.value).toLowerCase();

    response = await er_call_to_ajax_function({
      action: "er_get_events_by_championship",
      search,
    }, "event");
    const events = response.data;
  
    console.log("SEARCH CHAMPIONSHIP", events);
    event_list(events, true, "championship");
  });
});


async function er_get_post_by_id (id, post_type) {
  console.log("BY_ID", id, post_type);
  switch (post_type) {
    case "championship":
      if (id === -1) {
        await er_get_events_by_championship();
      } else {
        response = await er_call_to_ajax_function({
          action: "er_get_events_by_championship",
          championship_id: id,
        }, "event");
        const events = response.data;
      
        console.log("SEARCH CHAMPIONSHIP", events);
        event_list(events, true, "championship");
      }
      break;
    default:
      break;
  }
}