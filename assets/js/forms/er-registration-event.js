jQuery(document).ready(_ => {
  let isAffiliate = false;

  jQuery("#er-registration-form").ready(_ => {
    if (jQuery("#er-registration-form").length > 0 && !isAffiliate) jQuery("#er-registration-form").hide();
  });

  jQuery("#er-registration-btn").on("click", er_registration_form);
    
});


async function er_registration_form () {
  const id = jQuery("#er-registration-pilot-input").val();
  let searchParams = new URLSearchParams(window.location.search);

  if (searchParams.has('event')) {
    isAffiliate = false;
    jQuery("#er-registration-form").hide();
    jQuery("#er-registration-btn")?.html("Verificar");
    jQuery("#er-registration-verify").show();
  }

  try {
    const response = await er_call_to_ajax_function({
      action: "er_pilot_verification",
      id,
      event: searchParams.get('event'),
    }, "registration");
  
    if (response.data.isRegistered) {
      isAffiliate = false;
      jQuery("#er-registration-form").hide();
      jQuery("#er-registration-btn")?.html("Verificar");
      jQuery("#er-registration-verify").show();
      jQuery("#er-registration-verify").append(jQuery(uiNotifications('error', 'El Piloto ya fue registrado en este evento.')));
    } else if (response.data.isActive) {
      isAffiliate = true;
      jQuery("#er-registration-form").show();
      jQuery("input[name=postdata-1-post-title]").val(response.data.pilot.post_title);
      jQuery("input[name=postdata-1-post-title]").prop('readonly', true);
      jQuery("input[name=email-1]").val(response.data.pilot.email);
      jQuery("input[name=email-1]").prop('readonly', true);
      jQuery("input[name=number-2]").val(response.data.pilot.ID);
      jQuery("input[name=number-2]").prop('readonly', true);
      jQuery("input[name=text-6]").val(response.data.pilot.category.name);
      jQuery("input[name=text-6]").prop('readonly', true);
      er_registration_form_set_data(response.data.pilot, response.data.event);
      jQuery("#er-registration-verify").hide();
    } else {
      isAffiliate = false;
      jQuery("#er-registration-form").hide();
      jQuery("#er-registration-btn")?.html("Verificar");
      jQuery("#er-registration-verify").show();
      jQuery("#er-registration-verify").append(jQuery(uiNotifications('error', 'Piloto no afiliado.')));
    }
  } catch (error) {
    isAffiliate = false;
    jQuery("#er-registration-form").hide();
    jQuery("#er-registration-btn")?.html("Verificar");
    jQuery("#er-registration-verify").show();
    jQuery("#er-registration-verify").append(jQuery(uiNotifications('error', 'Piloto inválido.')));
  }
}

function er_registration_form_set_data (pilot, event) {
  const html = `
    <div class="mb-8">
      <h2>Bienvenid@ ${pilot.post_title}</h2>
      <p>Ahora podrás inscribirte al evento <strong>${event.post_title}.</strong></p>
    </div>
  `;

  jQuery("#er-registration-form").prepend(jQuery(html));
}