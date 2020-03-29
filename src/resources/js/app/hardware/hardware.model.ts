import { BaseModel } from '@app/common/model/basemodel';

export interface HardwareInterface
{
    id: number;
    name: string;
    serial_number: string;
    production_year: number;
    system_id: number;
    user_id: number;
}

export class Hardware extends BaseModel implements HardwareInterface
{
    private _id: number;
    private _name: string;
    private _serial_number: string;
    private _production_year: number;
    private _system_id: number;
    private _user_id: number;

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
    
    public set name(value: string) 
    {
        this._name = value;
    }

    public get name(): string
    {
        return this._name;
    }

    public set serial_number(value: string) 
    {
        this._serial_number = value;
    }

    public get serial_number(): string
    {
        return this._serial_number;
    }

    public set production_year(value: number) 
    {
        this._production_year = value;
    }

    public get production_year(): number
    {
        return this._production_year;
    }
    
    public set system_id(value: number) 
    {
        this._system_id = value;
    }

    public get system_id(): number
    {
        return this._system_id;
    }
    
    public set user_id(value: number) 
    {
        this._user_id = value;
    }

    public get user_id(): number
    {
        return this._user_id;
    }    

    fillModel(model: HardwareInterface|{}): Hardware 
    {
        this._id = this.setFieldValue(model['id'], 0);
        this._name = this.setFieldValue(model['name'], '');
        this._serial_number = this.setFieldValue(model['serial_number'], '');
        this._production_year = this.setFieldValue(model['production_year'], 0);
        this._system_id = this.setFieldValue(model['system_id'], null);
        this._user_id = this.setFieldValue(model['user_id'], null);        
        return this;
    }

    getArray(): HardwareInterface {
        return {
            id: this._id,
            name: this._name,
            serial_number: this._serial_number,
            production_year: this._production_year,
            system_id: this._system_id,
            user_id: this._user_id
        };
    }
}

