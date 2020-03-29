import { BaseModel } from "@app/common/model/basemodel";

export interface SystemInterface
{
    id: number;
    name: string;
}

export class System extends BaseModel implements SystemInterface
{
    private _id: number;
    private _name: string;

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

    fillModel(model: SystemInterface|{}): System 
    {
        this._id = this.setFieldValue(model['id'], 0);
        this._name = this.setFieldValue(model['name'], '');
        return this;
    }

    getArray(): SystemInterface {
        return {
            id: this._id,
            name: this._name,
        };
    }
}

