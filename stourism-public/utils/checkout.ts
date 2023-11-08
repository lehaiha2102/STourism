import { NextResponse } from "next/server";

const paypal = require('@paypal/checkout-server-sdk');

let clientId = "Ac5e4GqEHjI1z8qIbZpv60vDrH2fyG0HvvIxQV5Porhb2SrGr58E49WCwOUb5hvZtDqfD6dF0IfLkRtK";
let clientSecret = "EBcMf7iX8N6p1cXF0zFre0B80fPUAoJQJR_qpjhCs-X5iqwCi-H1gHO7EHnut9lWfPIReguqukSb0OhQ";

let environment = new paypal.core.SandboxEnvironment(clientId, clientSecret);
let client = new paypal.core.PayPalHttpClient(environment);

export function post(){
    return NextResponse.json({
        messenger: 'Payment prosessing'
    })
}

export function createOrderFunction(){
    
}