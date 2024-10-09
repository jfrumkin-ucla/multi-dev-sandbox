import jQuery from 'jquery';

(($ => {

  // This feature adapted from https://github.com/carlsednaoui/add-to@-calendar-buttons for use in SiteFarm.
  const MS_IN_MINUTES = 60 * 1000;

  const formatTime = date => date.toISOString().replace(/-|:|\.\d+/g, '');

  const calculateEndTime = event => event.end ?
    formatTime(event.end) :
    formatTime(new Date(event.start.getTime() + (event.duration * MS_IN_MINUTES)));

  const calendarGenerators = {
    google(event) {
      const startTime = formatTime(event.start);
      const endTime = calculateEndTime(event);

      const href = encodeURI([
        'https://www.google.com/calendar/render',
        '?action=TEMPLATE',
        `&text=${event.title || ''}`,
        `&dates=${startTime || ''}`,
        `/${endTime || ''}`,
        `&details=${event.description || ''}`,
        `&location=${event.address || ''}`,
        '&sprop=&sprop=name:'
      ].join(''));
      return `<a class="icon-google" target="_blank" href="${href}">Google Calendar<span class="visually-hidden">(link is external)</span></a>`;
    },

    yahoo(event) {
      const eventDuration = event.end ?
        ((event.end.getTime() - event.start.getTime()) / MS_IN_MINUTES) :
        event.duration;

      // Yahoo dates are crazy, we need to convert the duration from minutes to hh:mm
      const yahooHourDuration = eventDuration < 600 ?
        `0${Math.floor((eventDuration / 60))}` :
        `${Math.floor((eventDuration / 60))}`;

      const yahooMinuteDuration = eventDuration % 60 < 10 ?
        `0${eventDuration % 60}` :
        `${eventDuration % 60}`;

      const yahooEventDuration = yahooHourDuration + yahooMinuteDuration;

      // Remove timezone from event time
      const st = formatTime(new Date(event.start - (event.start.getTimezoneOffset() *
        MS_IN_MINUTES))) || '';

      const href = encodeURI([
        'http://calendar.yahoo.com/?v=60&view=d&type=20',
        `&title=${event.title || ''}`,
        `&st=${st}`,
        `&dur=${yahooEventDuration || ''}`,
        `&desc=${event.description || ''}`,
        `&in_loc=${event.address || ''}`
      ].join(''));

      return `<a class="icon-yahoo" target="_blank" href="${href}">Yahoo! Calendar<span class="visually-hidden">(link is external)</span></a>`;
    },

    ics(event, eClass, calendarName) {
      const startTime = formatTime(event.start);
      const endTime = calculateEndTime(event);

      const href = encodeURI(
        `data:text/calendar;charset=utf8,${[
          'BEGIN:VCALENDAR',
          'VERSION:2.0',
          'BEGIN:VEVENT',
          'URL:' + document.URL,
          'DTSTART:' + (startTime || ''),
          'DTEND:' + (endTime || ''),
          'SUMMARY:' + (event.title || ''),
          'DESCRIPTION:' + (event.description || ''),
          'LOCATION:' + (event.address || ''),
          'END:VEVENT',
          'END:VCALENDAR'].join('\n')}`);

      return `<a class="${eClass}" target="_blank" href="${href}">${calendarName} Calendar<span class="visually-hidden">(link is external)</span></a>`;
    },

    ical(event) {
      return this.ics(event, 'icon-ical', 'iCal');
    },

    outlook(event) {
      return this.ics(event, 'icon-outlook', 'Outlook');
    }
  };

  const generateCalendars = event => ({
    google: calendarGenerators.google(event),
    yahoo: calendarGenerators.yahoo(event),
    ical: calendarGenerators.ical(event),
    outlook: calendarGenerators.outlook(event)
  });

  // Make sure we have the necessary event data, such as start time and event duration
  const validParams = params => typeof params.data !== 'undefined' && typeof params.data.start !== 'undefined' &&
    (typeof params.data.end !== 'undefined' || typeof params.data.duration !== 'undefined');

  const generateMarkup = calendars => {
    const result = document.createElement('div');
    let calItems = '';

    result.innerHTML = '<a id="dd_btn" tabindex="0" aria-label="Add to my Calendar" aria-expanded ="false" class="dropdown__btn">+ Add to my Calendar</a>';

    Object.keys(calendars).forEach(services => {
      calItems += `<li class="dropdown__item">${calendars[services]}</li>`;
    });

    result.innerHTML += `<ul id="dd_content" class="dropdown__content visually-hidden">${calItems}</ul>`;

    result.innerHTML += '</div>';

    result.className = 'dropdown';

    return result;
  };

  const createCalendar = params => {
    if (!validParams(params)) {
      return;
    }

    return generateMarkup(generateCalendars(params.data));
  };

  // Configure Add to Calendar Button
  Drupal.behaviors.sitefarmOneAddToCal = {
    attach(context, settings) {

        once('addToCalendarBehavior', '.div.add-to-calendar-button', context).forEach(function (element) {
        const title = settings.event.title;
        const start = settings.event.start_date;
        const end = settings.event.end_date;
        const description = settings.event.desc;
        const addr = settings.event.address;

        const myCalendar = createCalendar({
          data: {
            // Event title
            title,

            // Event start date
            start: new Date(start),

            // You can also choose to set an end time
            // If an end time is set, this will take precedence over duration
            end: new Date(end),

            // Event Address
            address: addr,

            // Event Description
            description
          }
        });

        $('.add-to-calendar-button').append(myCalendar);
      });
    }
  };

  $(document).ready(() => {

    // Open/close Add Link content on button click or Enter key.
    $('#dd_btn').each(function () {
      $(this).on('click keypress', function (e) {
        if (e.keyCode === 13 || e.type === 'click') {
          if ($(this).siblings('#dd_content').hasClass('visually-hidden')) {
            $(this).siblings('#dd_content').removeClass('visually-hidden');
            $(this).attr('aria-expanded','true');
          }
          else {
            $(this).siblings('#dd_content').addClass('visually-hidden');
            $(this).attr('aria-expanded','false');
          }
          e.preventDefault();
        }
      })
    });
  });

}))(jQuery); // end jquery enclosure
