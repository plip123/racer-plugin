const uiNotifications = (type, message, timeout=0) => {
  const alert_type = type === 'error' ? 'danger' : type === 'success' ? 'success' : 'info';
  const color = type === 'error' ? '#dc3545' : type === 'success' ? '#28a745' : '#17a2b8';
  const html = `<div class="alert alert-${alert_type} text-center mx-5 p-4 er-alert text-[#FFFF] bg-[${color}]/[.6]">${message}</div>`;
  if (timeout > 0) setTimeout(_ => { jQuery(".er-alert").alert('close') }, timeout);
  return html;
}

const uiSpinner = (contentType) => {
  const spinner = `
    <svg class="animate-spin h-5 w-5 mr-3 ..." viewBox="0 0 24 24">
      <!-- ... -->
    </svg>
  `;
  
  switch (contentType) {
    case "event":
      jQuery("#er-show-event")?.empty();
      jQuery("#er-event-list")?.empty();
      jQuery("#er-show-event")?.append(spinner);
      jQuery("#er-event-list")?.append(spinner);
      break;
    default:
      break;
  }
}

const uiMonths = {
  "01": "Enero",
  "02": "Febrero",
  "03": "Marzo",
  "04": "Abril",
  "05": "Mayo",
  "06": "Junio",
  "07": "Julio",
  "08": "Agosto",
  "09": "Septiembre",
  "10": "Octubre",
  "11": "Noviembre",
  "12": "Diciembre",
};

function event_html_template (event) {
  const template = `
    <div id="er-${event.id}" class="flex rounded-2xl w-full overflow-hidden bg-white shadow-md max-h-[330px]">
      <image class="object-cover w-4/12 h-full" src="${event.image}" alt="${event.title}">

      <div class="flex flex-col gap-2 text-base p-5">
        <h5 class="font-bold">${event.title}</h5>
        <p><span class="dashicons dashicons-calendar-alt"></span> ${event.date}</p>
        <p>${event.description}</p>
        <p><span class="font-bold">Ubicación</span> ${event.location}</p>
        <p><span class="font-bold">Longitud</span> ${event.length} km</p>
        <p><span class="font-bold">Ancho</span> ${event.width} m</p>
        <p><span class="font-bold">Curvas</span> ${event.curves}</p>
        <p><span class="font-bold">Categorías compitiendo</span> ${event.categories}</p>
      </div>
    </div>
  `;

  return template;
}

function print_event_list (element, events) {
  let lastMonth = "";
  events.forEach((event) => {
    const currentMonth = (events[0].date.split("/"))[1];
    if(lastMonth !== currentMonth) {
      element.append(`
        <h5 class="capitalize text-xl text-[#500F12] font-bold">${uiMonths[currentMonth]}</h5>
      `);
      lastMonth = currentMonth;
    }

    element.append(event_html_template(event));
  });
}

function empty_list () {
  const showElement = jQuery("#er-show-event");
  const listElements = jQuery("#er-event-list");
  showElement.empty();
  listElements.empty();
  if (showElement.length > 0) showElement.append(uiNotifications('error', 'No hay eventos por el momento.'));
  if (listElements.length > 0) listElements.append(uiNotifications('error', 'No hay eventos por el momento.'));
}

function event_list (events, listAll) {
  const showElement = jQuery("#er-show-event");
  const listElements = jQuery("#er-event-list");
  showElement.empty();
  listElements.empty();

  if (showElement.length > 0) {
    if (!!events && events.length > 0) {
      print_event_list(showElement, listAll ? events : [events[0]]);
    } else {
      showElement.append(uiNotifications('error', 'No hay eventos por el momento.'));
    }
  }

  if (listElements.length > 0) {
    if (!!events && events.length > 0) {
      print_event_list(listElements, events);
    } else {
      listElements.append(uiNotifications('error', 'No hay eventos por el momento.'));
    }
  }
}