import React from 'react';
import moment from "moment";

const FlightCard = ({ flight }) => {
    const {
        departureTime,
        arrivalTime,
        duration,
        flightNumber,
        farePrice,
    } = flight;

    function formatDate(dateString) {
        const date = moment(dateString);
        return date.format('HH:mm dddd, D MMMM');
    }

    return (
        <div className="bg-white rounded-lg shadow-md p-6">
            <div className="flex items-center justify-between mb-4">
                <p className="text-xl font-semibold">{flightNumber}</p>
                <p className="text-lg font-semibold text-green-500">${farePrice}</p>
            </div>
            <div className="mb-4">
                <p className="text-gray-700">
                    Departure: <span className="font-semibold">{formatDate(departureTime)}</span>
                </p>
                <p className="text-gray-700">
                    Arrival: <span className="font-semibold">{formatDate(arrivalTime)}</span>
                </p>
            </div>
            <p className="text-gray-700">
                Duration: <span className="font-semibold">{duration}</span>
            </p>
        </div>
    );
};

export default FlightCard;
