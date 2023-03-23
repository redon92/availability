import React, { useState, useEffect } from "react";
import axios from "axios";
import Calendar from "react-calendar";
import "react-calendar/dist/Calendar.css";

const CalendarApp = ({ userId }) => {
    const [date, setDate] = useState(new Date());
    const [availability, setAvailability] = useState({});

    useEffect(() => {
        fetchAvailability();
    }, [date]);

    const formatDate = (date) => {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    };

    const toggleAvailability = async (day) => {
        try {
            const newAvailability = { ...availability };
            const dateKey = formatDate(new Date(date.getFullYear(), date.getMonth(), day));
            const isAvailable = newAvailability[dateKey];

            if (isAvailable === undefined) {
                newAvailability[dateKey] = true;
            } else {
                newAvailability[dateKey] = !isAvailable;
            }

            await axios.post('/api/availability/store', { user_id: userId, date: dateKey, is_available: newAvailability[dateKey] });
            setAvailability(newAvailability);
        } catch (error) {
            console.error(error);
        }
    };

    const updateAllAvailability = async (isAvailable) => {
        const startDate = new Date(date.getFullYear(), date.getMonth(), 1);
        const endDate = new Date(date.getFullYear(), date.getMonth() + 1, 0);
        const updatedAvailability = {};

        for (let d = new Date(startDate); d <= endDate; d.setDate(d.getDate() + 1)) {
            const dateKey = d.toISOString().split("T")[0];
            updatedAvailability[dateKey] = isAvailable;
        }

        try {
            await axios.post('/api/availability/update-all', { user_id: userId, is_available: isAvailable });
            setAvailability(updatedAvailability);
        } catch (error) {
            console.error(error);
        }
    };

    const fetchAvailability = async () => {
        try {
            const response = await axios.get(
                `/api/availability/get?user_id=${userId}&date=${date
                    .toISOString()
                    .split("T")[0]}`
            );
            const fetchedAvailability = response.data.availability;
            setAvailability(fetchedAvailability);
        } catch (error) {
            console.error(error);
        }
    };

    const renderDay = (day, { date }) => {
        const dateKey = formatDate(date);
        const isAvailable = availability[dateKey] !== undefined ? availability[dateKey] : false;
        const dayClasses =
            "text-center w-8 h-8 py-1 cursor-pointer rounded-full " +
            (isAvailable ? "bg-green-400 text-white" : "bg-red-400 text-gray-800");
        return (
            <div onClick={() => toggleAvailability(day)} className={dayClasses}>
                {day}
            </div>
        );
    };

    return (
        <div className="max-w-md mx-auto my-8 p-4 bg-white shadow">
            <Calendar
                onChange={setDate}
                value={date}
                onActiveStartDateChange={({ activeStartDate }) => setDate(activeStartDate)}
                tileContent={({ date, view }) => (view === "month" ? renderDay(date.getDate(), { date }) : null)}
                className="mb-4"
            />
            <button
                onClick={() => updateAllAvailability(true)}
                className="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded"
            >
                I'm available all the time
            </button>
            <button
                onClick={() => updateAllAvailability(false)}
                className="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded"
            >
                I'm available none of the time
            </button>
        </div>
    );
};

export default CalendarApp;
