import React, {useCallback, useEffect, useState} from 'react';
import AdminHeader from "../Utility/AdminHeader.tsx";
import {NavLink} from "react-router-dom";
import LineForm from "../Utility/LineForm.tsx";
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome';
import {faEye, faPencil, faPlus} from '@fortawesome/free-solid-svg-icons';
import {AppRootStateType, useAppSelector} from "../../Store/Store.ts";
import {useDispatch} from "react-redux";
import {SetCustomerAC} from "../../Store/Reducers/customerReducer.ts";
import Input from "../Utility/FormElements/Input.tsx";
import Button from "../Utility/Button.tsx";

// import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/react/20/solid'

function CustomerList() {
    const customers = useAppSelector<AppRootStateType>(state => state.customer)
    const dispatch = useDispatch();

    const [searchTerm, setSearchTerm] = useState('');
    const [offset, setOffset] = useState(0);
    const [count, setCount] = useState(1)


    const fetchCustomersHandler = useCallback(async (searchValue, offset = 0) => {
        try {
            const response = await fetch(`https://customer-dashboard.avency.dev/api/customers?title=${searchValue}&offset=${offset}`);
            const data = await response.json();
            setCount(data.count)
            dispatch(SetCustomerAC(data));
        } catch (error) {

        }
    }, [dispatch]);

    const onChangeHandlerSearchByCustomerName = (value: any) => {
        setSearchTerm(value);
    }

    const onClickHandler = (i: number) => {
        setOffset(i*100)
    }

    useEffect(() => {
        fetchCustomersHandler(searchTerm, offset);
    }, [fetchCustomersHandler, searchTerm, offset]);

    return (
        <div className="w-full">
            <AdminHeader
                headline={"Liste der Kunden"}
            >
            </AdminHeader>
            <div className="mt-6 ml-12 mr-12">
                <Input
                    title={"Suche nach Kundennamen"}
                    inputType="text"
                    classList="mb-3"
                    placeholder="Kundenname"
                    value={searchTerm}
                    onChange={onChangeHandlerSearchByCustomerName}
                />
            </div>

            <div className="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
                <div className="flex flex-1 justify-between sm:hidden">
                    <a
                        href="#"
                        className="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                    >
                        Previous
                    </a>
                    <a
                        href="#"
                        className="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                    >
                        Next
                    </a>
                </div>
                <div className="hidden sm:flex sm:flex-1 sm:items-center">
                    <div>
                        <nav className="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                            <a
                                href="#"
                                className="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"
                            >
                                <span className="sr-only">Previous</span>
                                {/*<ChevronLeftIcon className="h-5 w-5" aria-hidden="true" />*/}
                            </a>
                            {(() => {
                                const rows = []
                                for (let i = 0; i < count/100; i++) {
                                    rows.push(<a
                                            onClick={()=>onClickHandler(i)}
                                            href="#"
                                            className="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"
                                        >
                                            {i+1}
                                        </a>
                                    )
                                }
                                return rows;
                            })()}
                            <a
                                href="#"
                                className="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"
                            >
                                {/*<span className="sr-only">Next</span>*/}
                                {/*<ChevronRightIcon className="h-5 w-5" aria-hidden="true" />*/}
                            </a>
                        </nav>
                    </div>
                </div>
            </div>
            <div className="mt-6 ml-12 mr-12">
                <div className="flex justify-between">
                    <div className="w-[160px]"><h6>Kunde</h6></div>
                    <div className="w-[80px]"><h6>Kundennummer</h6></div>
                    <div className="w-[80px]"><h6>Prio</h6></div>
                    <div className="w-[160px]"><h6>Projektmanager</h6></div>
                    <div className="w-[160px]"><h6>Ansprech partner</h6></div>
                    <div className="w-[60px]"></div>
                </div>
                {customers.map(customer => {
                    return <div key={customer.identifier}>
                        <div className="flex justify-between">
                            <div className="w-[160px]">
                                {customer.abbreviation}
                                <div>---</div>
                                {customer.title}
                            </div>
                            <div className="w-[80px]">
                                {customer.customerNumber}
                            </div>
                            <div className="w-[80px]">
                                {customer.priority}
                            </div>
                            <div className="w-[160px]">
                                {customer.productManagerDefault.firstName}
                            </div>
                            <div className="w-[160px]">
                                {customer?.contactPerson?.lastname}
                            </div>
                            <div className="flex justify-between w-[60px]">
                                <NavLink to={"/usedSystemByCustomer/" + customer.identifier}>
                                    <FontAwesomeIcon icon={faPlus}/>
                                </NavLink>
                                <NavLink to={"/customerInfo/" + customer.identifier}>
                                    <FontAwesomeIcon icon={faEye}/>
                                </NavLink>
                                <NavLink
                                    to={"/customerForm/" + customer.identifier}
                                    className=''
                                >
                                    <FontAwesomeIcon icon={faPencil}/>
                                </NavLink>
                            </div>
                        </div>
                        <LineForm classList="mx-auto w-[100%] h-[2px] bg-gray-100 mt-3 mb-3"></LineForm>
                    </div>
                })}
            </div>
        </div>
    );
}

export default CustomerList;
