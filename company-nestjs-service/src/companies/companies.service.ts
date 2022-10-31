import { HttpService } from '@nestjs/axios';
import { HttpException, Injectable, NotFoundException } from '@nestjs/common';
import { InjectModel } from '@nestjs/mongoose';
import { Model, Types } from 'mongoose';
import { catchError } from 'rxjs';
import { CreateCompanyDto } from './dto/create-company.dto';
import { UpdateCompanyDto } from './dto/update-company.dto';
import { Company } from './entities/company.entity';

@Injectable()
export class CompaniesService {

  constructor(
      @InjectModel(Company.name) private readonly model: Model<Company>,
      private readonly httpService: HttpService
    ) { }

  create(createCompanyDto: CreateCompanyDto) {
    const company = new this.model(createCompanyDto)
    return company.save()
  }

  async findAll() {
    return await this.model.find();
  }

  async findOne(id: Types.ObjectId) {
    const company = await this.model.findById(id);
    if (!company) {
      throw new NotFoundException(`Company id ${id} not found`);
    }
    return company
  }

  async update(id: Types.ObjectId, updateCompanyDto: UpdateCompanyDto) {
    const company = await this.model.findByIdAndUpdate(id, updateCompanyDto, { new: true })
    if (!company) {
      throw new NotFoundException(`Company id ${id} not found`);
    }
    return company
  }

  async remove(id: Types.ObjectId) {
    const company = await this.model.findByIdAndDelete(id)
    if (!company) {
      throw new NotFoundException(`Company id ${id} not found`);
    }
    await this.deleteUsersFromCompany(id);
    return company
  }

  private async deleteUsersFromCompany(id: Types.ObjectId) {
    const request = this.httpService.delete(`${process.env.USERS_SERVICE_ENDPOINT}/company/${id}`).pipe(
      catchError((error) => {
        throw new HttpException('Request cant be processed, try again later', 400);
      })
    )
  }
}
