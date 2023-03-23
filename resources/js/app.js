import './bootstrap';
import React from 'react';
import ReactDOM from 'react-dom';
import CalendarApp from './components/CalendarApp';

import Alpine from 'alpinejs';
import FlightSearch from "./components/FlightSearch";

window.Alpine = Alpine;

Alpine.start();

if (document.getElementById('calendar-app')) {
    const userId = document.getElementById('calendar-app').getAttribute('data-user-id');
    ReactDOM.render(<CalendarApp userId={userId} />, document.getElementById('calendar-app'));
}

if (document.getElementById('flight-search-app')) {
    ReactDOM.render(<FlightSearch/>, document.getElementById('flight-search-app'));
}

