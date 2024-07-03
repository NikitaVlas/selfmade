export type CustomerType = {
    identifier: string
    title: string
    abbreviation?: string
    customerNumber?: number
    priority?: number
    productManagerDefault?: PersonType
    contactPerson: {
        firstname?: string
        lastname?: string
        email?: string
        phone?: string
    }
}

export type CustomerResponseType = {
    items: Array<CustomerType>
    count: number
}

export type PersonType = {
    identifier: string
    firstName?: string
    lastName?: string
    abbreviationPerson?: string
}

export type ProjectType = {
    identifier: string
    projectName: string
    projectNumber: string
    customer?: Array<CustomerType>
    projectLeader?: Array<PersonType>
}

export type UsedSystemType = {
    identifier: string
    title: string
    usedVersion?: string
    cookie?: string
    trackingsTools?: string
    sslCertificate?: string
    urlLocal?: string
    urlPreview?: string
    urlLive?: string
    customer?: CustomerType
    productManager?: PersonType
    leadDev?: PersonType
}
