import { BaseModel } from "@app/common/model/basemodel";

export interface TransferInterface
{
    id: number;
    user_id: number;
    hardware_id: number;
    hardware_name: string;
    type: number;
    date: any;
    remarks: string;
}

export class Transfer extends BaseModel implements TransferInterface
{
    private _id: number;
    private _user_id: number;
    private _hardware_id: number;
    private _hardware_name: string;
    private _type: number;
    private _date: any;
    private _remarks: string;

    constructor(
        model?: {}
    ) {
        super(model);
    }

    public set id(value: number) 
    {
        this._id = value;
    }

    public get id(): number
    {
        return this._id;
    }

    public set user_id(value: number) 
    {
        this._user_id = value;
    }

    public get user_id(): number
    {
        return this._user_id;
    }

    public set hardware_id(value: number) 
    {
        this._hardware_id = value;
    }

    public get hardware_id(): number
    {
        return this._hardware_id;
    }
    
    public set hardware_name(value: string) 
    {
        this._hardware_name = value;
    }

    public get hardware_name(): string
    {
        return this._hardware_name;
    }    

    public set type(value: number) 
    {
        this._type = value;
    }

    public get type(): number
    {
        return this._type;
    }

    public set date(value: any) 
    {
        this._date = value;
    }

    public get date(): any
    {
        return this._date;
    }

    public set remarks(value: string) 
    {
        this._remarks = value;
    }

    public get remarks(): string
    {
        return this._remarks;
    }

    fillModel(model: TransferInterface|{}): Transfer
    {
        this._id = this.setFieldValue(model['id'], 0);
        this._user_id = this.setFieldValue(model['user_id'], 0);
        this._hardware_id = this.setFieldValue(model['hardware_id'], 0);
        this._hardware_name = this.setFieldValue(model['hardware_name'], '');
        this._type = this.setFieldValue(model['type'], 0);
        this._date = this.setDateValue(model['date'], '');
        this._remarks = this.setFieldValue(model['remarks'], '');
        return this;
    }

    getArray(): TransferInterface {
        return {
            id: this._id,
            user_id: this._user_id,
            hardware_id: this._hardware_id,
            hardware_name: this._hardware_name,
            type: this._type,
            date: this._date.format('YYYY-MM-DD'),
            remarks: this._remarks,
        };
    }
}

