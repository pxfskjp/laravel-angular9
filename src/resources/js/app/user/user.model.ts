import { BaseModel } from "@app/common/model/basemodel";

export interface UserInterface
{
    id: number;
    firstname: string;
    lastname: string;
    email: string;
    hardware_id: number;
}

export class User extends BaseModel implements UserInterface
{
    private _id: number;
    private _firstname: string;
    private _lastname: string;
    private _email: string;
    private _hardware_id: number;

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

    public set firstname(value: string) 
    {
        this._firstname = value;
    }

    public get firstname(): string
    {
        return this._firstname;
    }

    public set lastname(value: string) 
    {
        this._lastname = value;
    }

    public get lastname(): string
    {
        return this._lastname;
    }

    public set email(value: string) 
    {
        this._email = value;
    }

    public get email(): string
    {
        return this._email;
    }
    
    public set hardware_id(value: number) 
    {
        this._hardware_id = value;
    }

    public get hardware_id(): number
    {
        return this._hardware_id;
    }    

    fillModel(model: UserInterface|{}): User
    {
        this._id = this.setFieldValue(model['id'], 0);
        this._firstname = this.setFieldValue(model['firstname'], '');
        this._lastname = this.setFieldValue(model['lastname'], '');
        this._email = this.setFieldValue(model['email'], '');
        this._hardware_id = this.setFieldValue(model['hardware_id'], null);        
        return this;
    }

    getArray(): UserInterface {
        return {
            id: this._id,
            firstname: this._firstname,
            lastname: this._lastname,
            email: this._email,
            hardware_id: this._hardware_id
        };
    }
}

