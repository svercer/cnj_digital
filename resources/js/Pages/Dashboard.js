import React, {useState} from 'react';
import Authenticated from '@/Layouts/Authenticated';
import {Head, useForm, usePage} from '@inertiajs/inertia-react';
import axios from "axios";
import FormData from 'form-data'
export default function Dashboard(props) {
    const [averagePrice, setAveragePrice] = useState(null)
    const [totalHousesSold, setTotalHousesSold] = useState(null)
    const [numberOfCrimes, setNumberOfCrimes] = useState(null)
    const [averagePricePerYearInLondon, setAveragePricePerYearInLondon] = useState([])
    const [message, setMessage] = useState('')
    const [data, setData] = useState({
        file: '',
        save_to_db: ''
    })

    console.log('message', message)
    const submit = (e) => {
        e.preventDefault()
        setMessage('')
        let dataToSend = new FormData
        dataToSend.append('save_to_db', data.save_to_db)
        dataToSend.append('file', data.file, data.file.name)
        axios.post('/upload', dataToSend, {
            headers: {
                'Content-type': 'multipart/form-data'
            }
        })
            .then(response => {
                console.log('response', response)
                setAveragePricePerYearInLondon([])
                setAveragePrice(null)
                setNumberOfCrimes(null)
                setTotalHousesSold(null)
                let data = response.data.data
                if (data.countOfAllHousesSold != null){
                    setTotalHousesSold(data.countOfAllHousesSold)
                }
                if (data.numberOfCrimesInYear != null){
                    setNumberOfCrimes(data.numberOfCrimesInYear)
                }
                if(data.totalSalesAveragePrice != null){
                    setAveragePrice(data.totalSalesAveragePrice)
                }
                if (data.averagePricePerYearInLondon){
                    setAveragePricePerYearInLondon(data.averagePricePerYearInLondon)
                }
                if (response.data.message){
                    setMessage(response.data.message)
                }
            })
            .catch(error => {
                if (error.length > 0){
                    setAveragePricePerYearInLondon([])
                    setAveragePrice(null)
                    setNumberOfCrimes(null)
                    setTotalHousesSold(null)
                }
            })
    }

    const handleChange = (e) => {
            const target = event.target;
            let value = target.type === 'checkbox' ? target.checked : target.files[0];
            const name = target.name;
            setData({
                ...data,
                [name]: value
            });
    }

    return (
        <Authenticated
            auth={props.auth}
            errors={props.errors}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>}
        >
            <Head title="Dashboard" />

            <div className="flex flex-col justify-center items-center my-10">
                <div className="shadow-lg p-6">
                    <form encType='multipart/form-data'>
                        <input
                            onChange={(e) => handleChange(e)}
                            className='mb-3'

                            type="file"
                               name="file"
                               id='file'
                        />
                        {/*{errors.file && <div className='text-red-600'>{errors.file}</div>}*/}
                        <br/>
                        <div className=''>
                            <input className='mr-2'
                                   type="checkbox"
                                   checked={data.save_to_db}
                                   name='save_to_db'
                                   value={data.save_to_db}
                                   id='save_to_db'
                                   onChange={(e) => handleChange(e)}

                            />
                            <label className='' htmlFor="save_to_db">Save to database</label>
                            {/*{errors.save_to_db && <div className='text-red-600'>errors.save_to_db</div>}*/}

                        </div>
                        <br/>
                        <button type='button' onClick={submit} className='bg-blue-300 py-2 px-4 rounded'>Submit</button>
                    </form>
                </div>
                {
                    message && <div className='bg-green-500 text-white py-2 px-4 my-3'>{message}</div>
                }
            </div>
            <div className='flex flex-col justify-center items-center'>
                <div>Average Price:  {averagePrice ?? 0}</div>
                <br/>
                <div>Total Houses Sold:  {totalHousesSold ?? 0}</div>
                <br/>
                <div>Number of Crimes:  {numberOfCrimes ?? 0}</div>
                <br/>
                <br/>
                <div>Average Price per year in London:
                    {
                        averagePricePerYearInLondon &&
                            <div>
                                <table className='table-fixed border-collapse border border-slate-500'>
                                    <thead>
                                        <tr>
                                            <th className='border border-slate-600 p-2'>Year</th>
                                            <th className='border border-slate-600 p-2'>Average Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {
                                            Object.keys(averagePricePerYearInLondon).map((year) => {
                                                return (<tr key={year}>
                                                        <td className='border border-slate-700'>{year}</td>
                                                        <td className='border border-slate-700'>{averagePricePerYearInLondon[year].average}</td>
                                                    </tr>)

                                            })
                                        }
                                    </tbody>
                                </table>
                            </div>
                    }
                </div>
            </div>
        </Authenticated>
    );
}
