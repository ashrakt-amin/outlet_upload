import React from 'react'

const pagintion = (totalCustomers,customersPerPage) => {

    const pageNumbers = [];
     for (let i= 1; i<= Math.ceil(totalCustomers / customersPerPage); i++) 
    { 
        pageNumbers.push(i); 
    }
  return pageNumbers;
}

export default pagintion