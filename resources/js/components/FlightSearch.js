import React, {useEffect, useState} from 'react';
import axios from 'axios';
import FlightCard from "./FlightCard";

const FlightSearch = () => {
    const [date, setDate] = useState('');
    const [flights, setFlights] = useState([]);
    const [validationErrors, setValidationErrors] = useState([]);

    useEffect(() => {
        console.log('started');
    }, []);

    const handleDateChange = (e) => {
        console.log('im inside of handlechange')

        setDate(e.target.value);
    };

    const searchFlights = async () => {
        console.log('im inside of searchFlights')
        setValidationErrors([]);

        try {
            const response = await axios.get(`http://localhost:8000/api/search-flights?date=${date}`);
            console.log('response data: ',response.data);

            setFlights(response.data);
        } catch (error) {
            console.error('Error fetching flights:', error?.response?.data?.errors);
            // console.error('Error fetching flights:', error.message);
            setValidationErrors(error?.response?.data?.errors);
            console.log('validation errors: ',validationErrors);
        }
    };

    return (
        <div className="max-w-md mx-auto my-8 p-4 bg-white shadow-lg rounded-lg">
            <div className="mb-6">
                <h1 className="text-2xl font-semibold mb-4">Search Flights</h1>
                <div className="flex items-center space-x-4">
                    <input
                        type="date"
                        value={date}
                        onChange={handleDateChange}
                        className="w-1/2 p-2 border border-gray-300 rounded"
                    />
                    <button
                        onClick={() => searchFlights()}
                        className="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded"
                    >
                        Search
                    </button>
                </div>
                {
                    validationErrors?.date?.length
                        ?
                        <div>
                            <span className="text-red-600">
                                {
                                    validationErrors?.date[0]
                                }
                            </span>
                        </div>
                        : <></>

                }
            </div>
            <div className="grid grid-cols-1">
                {flights.map((flight, index) => (
                    <FlightCard key={index} flight={flight} />
                ))}
            </div>
        </div>
    );
};

export default FlightSearch;
